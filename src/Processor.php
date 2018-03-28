<?php

namespace rdx\dompdfpages;

abstract class Processor implements ProcessorInterface {
	public function pre( $html ) {
		return $html;
	}
}
