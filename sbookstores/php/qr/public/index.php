<?php
error_reporting(E_ALL); // E_ALL // E_ERROR
date_default_timezone_set('America/Lima');

require_once __DIR__ . "/../vendor/autoload.php"; 
require_once __DIR__ . "/../Utilities/qr_factory.php"; 

use Utilities\qr_factory;
// Create a basic QR code

$qr_code = new qr_factory;
$img = $qr_code->generate("https://pagolink.niubiz.com.pe/pagoseguro/STANDARDBUSINESSUNIVERSITY/2239316","primer_qr","230");
// Create a response object

?>
