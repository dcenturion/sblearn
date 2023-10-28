<?php
namespace Utilities;

use Endroid\QrCode\Factory\QrCodeFactory;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\LabelAlignment;
use Endroid\QrCode\QrCode;
use Symfony\Component\HttpFoundation\Response;

// Create a basic QR code

// Set advanced options

class qr_factory{

    public function generate($message,$name,$size){
        $qrCodeFactory = new QrCodeFactory();
        $qrCode = $qrCodeFactory->create($message, [
            'writer' => 'png',
            'size' => $size,
            'margin' => 10,
        ]);
        $ruta =__DIR__.'/../img_qr/'.$name.'.png';
 
        // Directly output the QR code
        // header('Content-Type: '.$qrCode->getContentType(),false);
        // return $qrCode->writeString();
        
        // Save it to a file
        $qrCode->writeFile($ruta);
        
        // Create a response object
        // $response = new Response($qrCode->writeString(), Response::HTTP_OK, ['Content-Type' => $qrCode->getContentType()]);
    }
}


/*use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;

$result = Builder::create()
    ->writer(new PngWriter())
    ->writerOptions([])
    ->data('www.facebook.com')
    ->encoding(new Encoding('UTF-8'))
    ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
    ->size(800)
    ->margin(10)
    ->roundBlockSizeMode(new RoundBlockSizeModeMargin())
    ->logoPath(__DIR__.'/assets/logo.png')
    ->logoResizeToWidth(150)
    ->labelText('QR generado')
    ->labelFont(new NotoSans(20))
    ->labelAlignment(new LabelAlignmentCenter())
    ->validateResult(false)
    ->build();

    // Directly output the QR code
header('Content-Type: '.$result->getMimeType());
echo $result->getString();

// Save it to a file
$result->saveToFile(__DIR__.'/qr/qrcode.png');

// Generate a data URI to include image data inline (i.e. inside an <img> tag)
$dataUri = $result->getDataUri();
  */
?>