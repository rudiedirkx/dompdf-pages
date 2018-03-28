<?php

use Dompdf\Options;
use rdx\dompdfpages\Dompdf;
use rdx\dompdfpages\PageableDompdf;

require '../vendor/autoload.php';

$options = new Options();
$options->set('defaultPaperSize', 'a4');

$pdf = new PageableDompdf(new Dompdf($options));
$pdf->loadHtml(file_get_contents(__DIR__ . '/merger.html'));
$pdf->render();

header('Content-type: application/pdf; charset=utf-8');
echo $pdf->output();
