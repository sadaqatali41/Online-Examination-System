<?php
session_start();
$text=$_SESSION['code']=mt_rand(11111,99999);
header("Content-type:image/jpeg");
$font_size=28;
$width	  =130;
$height	  =35;
$image=imagecreate($width,$height);
$r=mt_rand(100,200);
$g=mt_rand(100,200);
$b=mt_rand(100,200);

imagecolorallocate($image,$r,$g,$b);

$r=mt_rand(0,100);
$g=mt_rand(0,100);
$b=mt_rand(0,100);

$font_color=imagecolorallocate($image,$r,$g,$b);

for($i=1;$i<=40;$i++)
{
	$x1=mt_rand(0,100);
	$y1=mt_rand(0,100);
	$x2=mt_rand(0,100);
	$y2=mt_rand(0,100);
	imageline($image, $x1, $y1, $x2, $y2, $font_color);
}
$angle=mt_rand(-10,10);

imagettftext($image, $font_size, $angle, 40, 22, $font_color, 'TTF_Image/ITCBLKAD.ttf', $text);

imagejpeg($image);

?>