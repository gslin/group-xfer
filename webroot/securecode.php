<?

header("Content-type: image/jpeg");

session_start();
$_SESSION['securecode'] = sprintf("%06d", rand(0, 999999));

$securecode = $_SESSION['securecode'];

$im = imagecreate(60, 20);
$background_color = imagecolorallocate($im, 127, 127, 127);
$text_color = imagecolorallocate($im, 63, 63, 63);
imagestring($im, 5, 4, 2, $securecode, $text_color);

imagejpeg($im);
imagedestroy($im);

?>
