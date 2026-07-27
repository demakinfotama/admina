<?php

namespace App\Core;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfHelper
{
    public static function render(string $html, string $filename = 'document', string $paperSize = 'A4'): void
    {
        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', false);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper($paperSize, 'portrait');
        $dompdf->render();
        $dompdf->stream($filename . '.pdf', ['Attachment' => true]);
        exit;
    }

    public static function save(string $html, string $savePath, string $paperSize = 'A4'): void
    {
        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isRemoteEnabled', false);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper($paperSize, 'portrait');
        $dompdf->render();

        file_put_contents($savePath, $dompdf->output());
    }
}
