<?php

use Dompdf\Options;
use rdx\dompdfpages\Dompdf;
use rdx\dompdfpages\EverythingRedProcessor;
use rdx\dompdfpages\ProcessableDompdf;

require '../vendor/autoload.php';

$options = new Options();
$options->set('defaultPaperSize', 'a4');

$pdf = new ProcessableDompdf(new Dompdf($options), [new EverythingRedProcessor('yellow')]);
$pdf->loadHtml(file_get_contents(__DIR__ . '/red.html'));
$pdf->render();

header('Content-type: application/pdf; charset=utf-8');
echo $pdf->output();
