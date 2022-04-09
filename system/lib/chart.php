<?php

header("Content-type: image/png");
$percent    = $_GET["p"];
settype($percent,"integer");
$grad       = 3.6 * $percent;
$realWidth  = 150;
$realHeight = 150;
$srcWidth   = $realWidth * 2;
$srcHeight  = $realHeight * 2;

$img        = imagecreatetruecolor($srcHeight, $srcHeight);
$img2       = imagecreatetruecolor($realWidth, $realWidth);
$cl_back    = imagecolorallocate($img, 255, 255, 255);
$cl_circle  = imagecolorallocate($img, 255, 0, 0);
$cl_percent = imagecolorallocate($img, 0, 0, 255);

imagefill($img, 1, 1, $cl_back);
imagecolortransparent($img, $cl_back);
imagefilledellipse($img, $realWidth, $realHeight, $realWidth, $realHeight, $cl_circle);
if ($grad>0) imagefilledarc($img, $realWidth, $realHeight, $realWidth, $realHeight, 0, $grad, $cl_percent,IMG_ARC_PIE);
imagecopyresampled($img2, $img, 0, 0, 0, 0, $realWidth, $realHeight, $srcWidth, $srcHeight);
imagePNG($img2);

