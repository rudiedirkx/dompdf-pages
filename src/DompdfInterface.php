<?php

namespace rdx\dompdfpages;

use Dompdf\Options;

interface DompdfInterface {

    public function setOptions( Options $options );
    public function getOptions();

	public function render();
	public function output( $options = null );
	public function loadHtml( $str, $encoding = 'UTF-8' );

	public function clone();

}
