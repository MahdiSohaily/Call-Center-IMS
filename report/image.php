<?

$width = 800;  // Set the width of the new image
$height = 600; // Set the height of the new image
$image = imagecreatetruecolor($width, $height);

$bg_color = imagecolorallocate($image, 255, 255, 255); // White background
imagefill($image, 0, 0, $bg_color);

$photo1 = imagecreatefromjpeg('bill.png');
$photo2 = imagecreatefromjpeg('ordered.png');

imagecopy($image, $photo1, 0, 0, 0, 0, imagesx($photo1), imagesy($photo1));
imagecopy($image, $photo2, 400, 200, 0, 0, imagesx($photo2), imagesy($photo2));

imagejpeg($image, 'combined_image.jpg', 90); // Save the new image as 'combined_image.jpg' with 90% quality
imagedestroy($image);
imagedestroy($photo1);
imagedestroy($photo2);
