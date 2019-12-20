<?php

defined('BASEPATH') OR exit('No direct script access allowed');

define('DOMPDF_ENABLE_AUTOLOAD', TRUE);
require_once('dompdf/dompdf_config.inc.php');


class Pdfgenerator {

    public function generate($html, $filename = '', $stream = FALSE, $paper = 'A4', $orientation = 'portrait', $pass) {
        
        $dompdf = new Dompdf();
        $dompdf->load_html($html);
        $dompdf->set_paper($paper, $orientation);
        $dompdf->render();
        $dompdf->get_canvas()->get_cpdf()->setEncryption($pass);
        if ($stream) {
            $dompdf->stream($filename . ".pdf", array("Attachment" => 1));
        } else {
            return $dompdf->output();
        }
    }

}
