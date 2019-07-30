<?php
session_start();
header("Content-type: image/jpeg");
$captcha_code = mt_rand(11111,99999);
$_SESSION["code"] = $captcha_code;
$image = imagecreatetruecolor(70,30);
$captcha_background = imagecolorallocate($image, 255, 160, 119);
imagefill($image,0,0,$captcha_background);
$captcha_text_color = imagecolorallocate($image, 0, 0, 0);
imagestring($image, 5, 5, 5, $captcha_code, $captcha_text_color);

imagejpeg($image);

?>