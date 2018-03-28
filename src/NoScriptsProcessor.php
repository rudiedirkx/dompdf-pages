<?php

namespace rdx\dompdfpages;

use rdx\dompdfpages\Processor;

class NoScriptsProcessor extends Processor {
	public function pre( $html ) {
		return preg_replace('#<script.*?>[\s\S]*?</script>#', '', $html);
	}
}
