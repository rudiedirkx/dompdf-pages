<?php

namespace rdx\dompdfpages;

interface ProcessorInterface {

	public function pre( string $html ) : string;

}
