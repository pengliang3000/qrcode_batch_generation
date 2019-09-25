<?php
include('D:/Users/phpqrcode/qrlib.php');
//include('D:/Users/phpqrcode/config.php');

$outputDir = "d:/output/";
$resultDir = "d:/output/result/";

$startNumber = 2501;
$endNumber = 3000;
//$endNumber = 222;
$prefix = 'PF201910';
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
	QRcode::png($word,$file,QR_ECLEVEL_L,8,0);
}

function compose2Image($src_file,$dst_file){	
	global $resultDir;
	//$path_1 = "C:/programs/php7/exec/base.png";
	//$path_2 = 'http://tb1.bdstatic.com/tb/static-client/img/webpage/wap_code.png';
	//$path_2 = "C:/programs/php7/exec/one.png";
	$image_1 = imagecreatefrompng($dst_file);
	imagesavealpha($image_1,true);
	$image_2 = imagecreatefrompng($src_file);
	imagesavealpha($image_2,true);
//$cut = imagecreatetruecolor($src_w, $src_h);
//imagecopy($cut, $dst_im, 0, 0, $dst_x, $dst_y, $src_w, $src_h);
//imagecopy($cut, $src_im, 0, 0, $src_x, $src_y, $src_w, $src_h);
//imagecopymerge($dst_im, $cut, $dst_x, $dst_y, 0, 0, $src_w, $src_h, $pct);
	
	imagecopymerge($image_1, $image_2, 94, 98, 0, 0, imagesx($image_2), imagesy($image_2), 100);
	
	$font = 'C:/Users/pengl/AppData/Local/Microsoft/Windows/Fonts/SourceHanSansCN-Medium.otf';
	$black = imagecolorallocate($image_1, 0x00, 0x00, 0x00);
	$info = pathinfo($src_file);
	$merge = $resultDir . $info['basename'];
	imagefttext($image_1, 18, 0, 66, 338, $black, $font, implode(' ',str_split($info['filename'])));//$info['filename']);

	$flag = imagepng($image_1,$merge);
	imagedestroy($image_1);
	imagedestroy($image_2);
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

//$path_1 = 'https://ss0.bdstatic.com/5aV1bjqh_Q23odCf/static/superman/img/logo/bd_logo1_31bdc765.png';

?>