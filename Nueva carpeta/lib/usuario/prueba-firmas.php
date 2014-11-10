<?php

$img = imagecreatefromjpeg("./firmas/0db5de37568e9471ad555c8e5e33776b.jpg");
//find the size of the borders
$b_top = 0;
$b_btm = 0;
$b_lft = 0;
$b_rt = 0;

//top
for(; $b_top < imagesy($img); ++$b_top) {
  for($x = 0; $x < imagesx($img); ++$x) {
	  print imagecolorat($img, $x, $b_top)."/n";
    if(imagecolorat($img, $x, $b_top) != 16777215) {
       break 2; //out of the 'top' loop
    }
  }
}

//bottom
for(; $b_btm < imagesy($img); ++$b_btm) {
  for($x = 0; $x < imagesx($img); ++$x) {
    if(imagecolorat($img, $x, imagesy($img) - $b_btm-1) != 16777215) {
       break 2; //out of the 'bottom' loop
    }
  }
}

//left
for(; $b_lft < imagesx($img); ++$b_lft) {
  for($y = 0; $y < imagesy($img); ++$y) {
    if(imagecolorat($img, $b_lft, $y) != 16777215) {
       break 2; //out of the 'left' loop
    }
  }
}

//right
for(; $b_rt < imagesx($img); ++$b_rt) {
  for($y = 0; $y < imagesy($img); ++$y) {
    if(imagecolorat($img, imagesx($img) - $b_rt-1, $y) != 16777215) {
       break 2; //out of the 'right' loop
    }
  }
}

//copy the contents, excluding the border
$newimg = imagecreatetruecolor(
    imagesx($img)-($b_lft+$b_rt), imagesy($img)-($b_top+$b_btm));

imagecopy($newimg, $img, 0, 0, $b_lft, $b_top, imagesx($newimg), imagesy($newimg));

//finally, output the image
header("Content-Type: image/jpeg");
imagejpeg($newimg);

/* Crear el objeto y leer la imagen 
$im = new Imagick("./firmas/0db5de37568e9471ad555c8e5e33776b.jpg");*/

/* Recortar la imagen. 
$im->trimImage(0);*/

/* Imprimir la imagen 
header("Content-Type: image/" . $im->getImageFormat());
echo $im;*/



?>