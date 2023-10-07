<?php

$width = 800;  // Set the width of the new image
$height = 600; // Set the height of the new image
$image = imagecreatetruecolor($width, $height);

$bg_color = imagecolorallocate($image, 255, 255, 255); // White background
imagefill($image, 0, 0, $bg_color);

$photo1 = imagecreatefromjpeg('2.jpg'); // Load 'bill.png' as a PNG
if (!$photo1) {
    die('Failed to load photo1');
}

$photo2 = imagecreatefromjpeg('1.jpeg'); // Load 'ordered.png' as a JPEG
if (!$photo2) {
    die('Failed to load photo2');
}

imagecopy($image, $photo1, 0, 0, 0, 0, imagesx($photo1), imagesy($photo1));
imagecopy($image, $photo2, 400, 200, 0, 0, imagesx($photo2), imagesy($photo2));

// Define the specific directory where you want to save the image
$saveDirectory = './newImages/';

// Save the new image to the specified directory with 90% quality
$imagePath = $saveDirectory . 'combined_image.jpg';
imagejpeg($image, $imagePath, 90);

// Clean up resources
imagedestroy($image);
imagedestroy($photo1);
imagedestroy($photo2);

// Display a message with the saved image path
echo 'Image saved at: ' . $imagePath;
