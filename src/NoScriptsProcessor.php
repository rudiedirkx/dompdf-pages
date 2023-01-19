<?php

namespace rdx\dompdfpages;

use rdx\dompdfpages\Processor;

class NoScriptsProcessor extends Processor {
	public function pre( string $html ) : string {
		return preg_replace('#<script.*?>[\s\S]*?</script>#', '', $html);
	}
}
