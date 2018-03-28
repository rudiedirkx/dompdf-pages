<?php

namespace rdx\dompdfpages;

use Dompdf\Options;

abstract class DompdfDecorator implements DompdfInterface {

	/**
	 * @var DompdfInterface
	 */
	protected $pdf;

	public function __construct( DompdfInterface $pdf ) {
		$this->pdf = $pdf;
	}

    public function setOptions( Options $options ) {
    	$this->pdf->setOptions($options);
    }

    public function getOptions() {
    	return $this->pdf->getOptions();
    }

	public function render() {
		$this->pdf->render();
	}

	public function output( $options = null ) {
		return $this->pdf->output($options);
	}

	public function loadHtml( $html, $encoding = 'UTF-8' ) {
		$this->pdf->loadHtml($html, $encoding);
	}

	public function clone() {
		$this->pdf = $this->pdf->clone();
		return $this;
	}

	public function __call( $name, array $arguments ) {
		return call_user_func_array([$this->pdf, $name], $arguments);
	}

}
