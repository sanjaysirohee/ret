<?php
session_start();

// Generate a random alphanumeric string
$characters = '123456789';
$text = substr(str_shuffle($characters), 0, 4); // Create a 4-character alphanumeric string
$_SESSION["vercode"] = $text;

$height = 30; // Height of the image
$width = 120; // Width of the image
$image_p = imagecreate($width, $height);

// Allocate colors
$background_color = imagecolorallocate($image_p, 0, 0, 0); // Black background
$text_color = imagecolorallocate($image_p, 255, 255, 255); // White text

// Set font size
$font_size = 5; // Maximum font size for imagestring is 5

// Center the text dynamically
$text_x_position = ($width - (imagefontwidth($font_size) * strlen($text))) / 2; // Calculate X position
$text_y_position = ($height - imagefontheight($font_size)) / 2; // Calculate Y position
imagestring($image_p, $font_size, $text_x_position, $text_y_position, $text, $text_color);

// Output the CAPTCHA image
header("Content-Type: image/png");
imagepng($image_p);
imagejpeg($image_p, null, 80); // Output the image with 80% quality

// Free memory
imagedestroy($image_p);



//session_start(); 
//$text = rand(100000,999999); 
//$_SESSION["vercode"] = $text; 
//$height = 25; 
//$width = 65;   
//$image_p = imagecreate($width, $height); 
//$black = imagecolorallocate($image_p, 0, 0, 0); 
//$white = imagecolorallocate($image_p, 255, 255, 255); 
//$font_size = 14; 
//imagestring($image_p, $font_size, 6, 6, $text, $white); 
//imagejpeg($image_p, null, 80);
?>