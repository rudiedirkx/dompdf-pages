<?php

namespace rdx\dompdfpages;

use DOMDocument;
use Dompdf\Canvas;
use Dompdf\FontMetrics;
use rdx\dompdfpages\Processor;

class PagerProcessor extends Processor {

	protected array $names = [];
	protected array $templates = [];

	protected function parseAttributes( $str ) {
		$attrs = [
			'pages' => null,
		];

		if ( $str ) {
			$html = '<div ' . $str . '>attr</div>';
			$dom = new DOMDocument;
			$dom->loadXML($html);
			$el = $dom->firstChild;

			$pages = (string) $el->getAttribute('pages');
			if ( $pages != '' ) {
				$attrs['pages'] = $pages;
			}
		}

		return $attrs;
	}

	protected function parsePages( ?string $pages ) {
		if ( !$pages ) {
			return [0, 0];
		}

		if ( preg_match('#^\d+$#', $pages) ) {
			return [(int) $pages, (int) $pages];
		}

		if ( preg_match('#^(\d+)?\-(\d+)?$#', $pages, $match) ) {
			return [(int) @$match[1], (int) @$match[2]];
		}

		return [0, 0];
	}

	public function pre( string $html ) : string {
		$this->dompdf->setCallbacks([
			...$this->dompdf->getCallbacks(),
			[
				'event' => 'end_document',
				'f' => function(int $pageNum, int $pageCount, Canvas $canvas, FontMetrics $fonts) {
					$pdf = $canvas->get_cpdf();
					foreach ($this->names as [$name, $attributes]) {
						list($firstPage, $lastPage) = $this->parsePages($attributes['pages']);

						if ((!$firstPage || $pageNum >= $firstPage) && (!$lastPage || $pageNum <= $lastPage)) {
							$oid = $GLOBALS[$name][$pageNum] ?? null;
							if ($oid !== null) {
								$pdf->objects[$oid]['c'] = strtr($pdf->objects[$oid]['c'], [
									':n' => $pageNum,
									':t' => $pageCount,
								]);
								$canvas->add_object($oid);
							}
						}
					}
				},
			],
		]);

		$html = preg_replace_callback('#<dompdf\-pager(.*?)>([\s\S]+?)</dompdf\-pager>#', function($match) {
			list(, $attributes, $content) = $match;

			$content = trim($content);
			$attributes = $this->parseAttributes(trim($attributes));

			$name = 'p' . rand();
			$this->names[] = [$name, $attributes];

			return implode('', [
				'<script type="text/php">',
				'$GLOBALS["' . $name . '"][$PAGE_NUM] = $pdf->open_object();',
				'</script>',
				$content,
				'<script type="text/php">',
				'$pdf->close_object();',
				'</script>',
			]);
		}, $html);

		return $html;
	}
}
