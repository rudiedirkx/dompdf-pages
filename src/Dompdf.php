<?php

namespace rdx\dompdfpages;

use Dompdf\Dompdf as BaseDompdf;

class Dompdf extends BaseDompdf implements DompdfInterface {
	// Stub to implement DompdfInterface

	public function clone() {
		return new $this($this->getOptions());
	}
}
