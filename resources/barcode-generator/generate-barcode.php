<?php

	include('vendor/autoload.php');

	$text = isset($_GET['text'])?$_GET['text']:'1234';
	
	

	

	$generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
	echo $generator->getBarcode($text, $generator::TYPE_CODE_128);

	header('Content-type: image/png');

	


?>
