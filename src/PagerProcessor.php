<?php

namespace rdx\dompdfpages;

use DOMDocument;
use rdx\dompdfpages\Processor;

class PagerProcessor extends Processor {
	protected function parseAttributes( $str ) {
		$attrs = [
			'pages' => null,
		];

		if ( $str ) {
			$html = '<div ' . $str . '>attr</div>';
			$dom = new DOMDocument;
			$dom->loadXML($html);
			$el = $dom->firstChild;

			$pages = (string) $el->getAttribute('pages');
			if ( $pages != '' ) {
				$attrs['pages'] = $pages;
			}
		}

		return $attrs;
	}

	protected function parsePages( $pages ) {
		if ( preg_match('#^\d+$#', $pages) ) {
			return [(int) $pages, (int) $pages];
		}

		if ( preg_match('#^(\d+)?\-(\d+)?$#', $pages, $match) ) {
			return [(int) @$match[1], (int) @$match[2]];
		}

		return [0, 0];
	}

	public function pre( $html ) {
		return preg_replace_callback('#<dompdf\-pager(.*?)>([\s\S]+?)</dompdf\-pager>#', function($match) {
			list(, $attributes, $content) = $match;

			$content = trim($content);
			$attributes = $this->parseAttributes(trim($attributes));

			list($firstPage, $lastPage) = $this->parsePages($attributes['pages']);

			$name = 'p' . rand();

			return implode("\n", [
				'<script type="text/php">$GLOBALS["' . $name . '"] = $pdf->open_object();</script>',
				$content,
				'<script type="text/php">',
				'$pdf->close_object();',
				'$pdf->page_script(\'',
				'	if ((!' . $firstPage . ' || $PAGE_NUM >= ' . $firstPage . ') && (!' . $lastPage . ' || $PAGE_NUM <= ' . $lastPage . ')) {',
				'		$nobj = $pdf->open_object(); $pdf->close_object();',
				'		$pdf->get_cpdf()->objects[$nobj] = $pdf->get_cpdf()->objects[ $GLOBALS["' . $name . '"] ];',
				'		$pdf->get_cpdf()->objects[$nobj]["c"] = strtr($pdf->get_cpdf()->objects[$nobj]["c"], [',
				'			"%n" => $PAGE_NUM,',
				'			"%t" => $PAGE_COUNT,',
				'		]);',
				'		$pdf->add_object($nobj);',
				'	}',
				'\');',
				'</script>',
			]);
		}, $html);
	}
}
