<?php

namespace rdx\dompdfpages;

abstract class Processor implements ProcessorInterface {

	protected DompdfInterface $dompdf;

	public function setDompdf( DompdfInterface $dompdf ) : void {
		$this->dompdf = $dompdf;
	}

	public function pre( string $html ) : string {
		return $html;
	}

}
