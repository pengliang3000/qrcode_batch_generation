<?php
//You can get qrcode library for php there - https://sourceforge.net/projects/phpqrcode/  
include('D:/Users/phpqrcode/qrlib.php');

//dir that save qrcode files 
$outputDir = "d:/output/";
//dir that save final files 
$resultDir = "d:/output/result/";

$startNumber = 1;
$endNumber = 3000;
//$endNumber = 222;
$prefix = 'SWF201910';
$baseImage = 'd:/baseImage.png';
for($i=$startNumber;$i<=$endNumber;$i++){
	$word = $prefix . str_pad($i,4,0,STR_PAD_LEFT);
	$file = $outputDir.$word.'.png';
	create2wCode($word,$file);
	if(file_exists($file)){
		compose2Image($file,$baseImage);
		echo "$file\n";
	}
}

function create2wCode($word,$file){
	/*1st : content of string 
         *2st : where to save the qrcode file
	 *3st QR_ECLEVEL_L
	 *4st Qr code image size
	 *5st qr code margin default:3, 0 means no margin
	QRcode::png($word,$file,QR_ECLEVEL_L,8,0);
}

function compose2Image($src_file,$dst_file){	
	global $resultDir;	
	$image_1 = imagecreatefrompng($dst_file);
	//Reserve the transparent area of the picture,or you will see background of the image fill in white 
	imagesavealpha($image_1,true);
	$image_2 = imagecreatefrompng($src_file);
	imagesavealpha($image_2,true);
	
	imagecopymerge($image_1, $image_2, 94, 98, 0, 0, imagesx($image_2), imagesy($image_2), 100);
	//This font file is free,can use it for any purpose without any pay
	$font = 'C:/Users/pengl/AppData/Local/Microsoft/Windows/Fonts/SourceHanSansCN-Medium.otf';
	$black = imagecolorallocate($image_1, 0x00, 0x00, 0x00);
	$info = pathinfo($src_file);
	$merge = $resultDir . $info['basename'];
	imagefttext($image_1, 18, 0, 66, 338, $black, $font, implode(' ',str_split($info['filename'])));
        //output qr code files
	$flag = imagepng($image_1,$merge);
	imagedestroy($image_1);
	imagedestroy($image_2);
	
	
	//Modify  meta data of png files,I want  PNG files with resolution of 300.
	$file = file_get_contents($merge);
	$len = pack("N", 9);
	$sign = pack("A*", "pHYs");
	$data = pack("NNC", 300 * 39.37, 300 * 39.37, 0x01);
	$checksum = pack("N", crc32($sign . $data));
	$phys = $len . $sign . $data . $checksum;
	$pos = strpos($file, "pHYs");
	if ($pos > 0) {
		$file = substr_replace($file, $phys, $pos - 4, 21);
	} else {
		$pos = 33;
		$file = substr_replace($file, $phys, $pos, 0);
	}
	file_put_contents($merge,$file);
	return $flag;
}



?>
