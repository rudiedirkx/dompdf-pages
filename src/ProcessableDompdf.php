<?php

namespace rdx\dompdfpages;

use Dompdf\Dompdf;
use rdx\dompdfpages\Processor;

class ProcessableDompdf extends DompdfDecorator {

	/**
	 * @var Processor[][]
	 */
	protected array $processors = [];

	public function __construct( DompdfInterface $pdf, array $processors = [] ) {
		parent::__construct($pdf);

		$options = $pdf->getOptions();
		$options->set('isPhpEnabled', true);

		foreach ( $processors as $processor ) {
			$this->addProcessor($processor);
		}
	}

	public function addProcessor( Processor $processor, int $weight = 0 ) {
		$this->processors[$weight][] = $processor;
		return $this;
	}

	public function loadHtml( $html, $encoding = 'UTF-8' ) {
		ksort($this->processors);
		foreach ( $this->processors as $processors ) {
			foreach ( $processors as $processor ) {
				$processor->setDompdf($this);
				$html = $processor->pre($html);
			}
		}

		parent::loadHtml($html, $encoding);
	}

}
