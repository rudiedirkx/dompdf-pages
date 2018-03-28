<?php

namespace rdx\dompdfpages;

use DOMDocument;
use DOMElement;
use iio\libmergepdf\Merger;
use rdx\dompdfpages\Dompdf;
use setasign\Fpdi\Fpdi;

class PageableDompdf extends DompdfDecorator {

	protected $html = '';
	protected $htmlEncoding = '';

	protected $fpdi;
	protected $merger;

	protected $template;
	protected $bodyPlaceholder;

	public function render() {
		$bodies = $this->getBodies();
		if ( count($bodies) == 1) {
			$this->pdf->loadHtml($this->html);
			$this->pdf->render();
			return;
		}

		$this->fpdi = new Fpdi;
		$this->merger = new Merger($this->fpdi);

		foreach ( $bodies as $i => $body ) {
			$pdf = $this->pdf->clone();

			$html = str_replace($this->bodyPlaceholder, $body, $this->template);

			$pdf->loadHtml($html);
			$pdf->render();
            $this->merger->addRaw($pdf->output());

            if ( $i == 0 ) {
            	$this->addMetaHtml($pdf->getDom());
            }
		}
	}

	protected function addMetaHtml( DOMDocument $dom ) {
		$title = $dom->getElementsByTagName("title");
		if ( $title->length ) {
			$this->addMetaNode('title', $title->item(0));
		}

		$metas = $dom->getElementsByTagName('meta');
		/** @var DOMElement $meta */
		foreach ( $metas as $meta ) {
			$name = $meta->getAttribute('name');
			$this->addMetaNode($name, $meta);
		}
	}

	protected function addMetaNode( $name, DOMElement $node ) {
		if ( in_array(strtolower($name), ['title', 'author', 'subject', 'keywords', 'creator']) ) {
			$method = [$this->fpdi, 'Set' . $name];
			$value = trim($node->nodeValue);
			call_user_func($method, $value, true);
		}
	}

	protected function getBodies() {
		preg_match_all('#<body[^>]*>[\w\W]+?</body>#', $this->html, $matches);
		$this->template = preg_replace('#<body[\w\W]+</body>#', ($this->bodyPlaceholder = '<!-- ' . rand() . ' -->'), $this->html);

		return $matches[0];
	}

	public function output( $options = null ) {
		return $this->merger ? $this->merger->merge() : $this->pdf->output($options);
	}

	public function loadHtml( $str, $encoding = 'UTF-8' ) {
		$this->html = $str;
		$this->htmlEncoding = $encoding;
	}

}
