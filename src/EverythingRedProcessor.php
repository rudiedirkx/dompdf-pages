<?php

namespace rdx\dompdfpages;

use rdx\dompdfpages\Processor;

class EverythingRedProcessor extends Processor {
	protected $color = 'red';

	public function __construct($color = null) {
		if ($color) {
			$this->color = $color;
		}
	}

	public function pre( $html ) {
		return preg_replace('#(<style.*?>)#', "$1\n* { background-color: {$this->color}; }\n\n", $html);
	}
}
