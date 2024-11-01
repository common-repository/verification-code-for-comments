<?php
session_start();
class CCheckCodeFile{
var $mCheckCode   = ''; //verification code
var $mCheckImage  = ''; //verification code image
var $mDisturbColor  = ''; //
var $mCheckImageWidth = '120'; //
var $mCheckImageHeight  = '20'; //

function CCheckCodeFile(){
  $this->mCheckCodeNum = $_GET['l'];
  $this->mCheckMethod = $_GET['m'];
}

function OutFileHeader(){
  header ("Content-type: image/png");
 }
function CreateCheckCode(){
  $this->mCheckCode = strtoupper(substr(md5(rand()),0,$this->mCheckCodeNum));
  $this->mCheckCode = str_replace("0","8",$this->mCheckCode);
  $this->mCheckCode = str_replace("O","P",$this->mCheckCode); 
  return $this->mCheckCode;
 }
function CreateImage(){
  $this->mCheckImage = @imagecreate ($this->mCheckImageWidth,$this->mCheckImageHeight);
  imagecolorallocate ($this->mCheckImage, 0x99, 0xff, 0x99);
  return $this->mCheckImage;
 }

function SetDisturbColor(){//disturb
  for ($i=0;$i<=128;$i++){
   $this->mDisturbColor = imagecolorallocate ($this->mCheckImage, rand(0,255), rand(0,255), rand(0,255));
   imagesetpixel($this->mCheckImage,rand(2,$this->mCheckImageWidth-2),rand(2,$this->mCheckImageHeight-2),$this->mDisturbColor);
  }
  
  for( $i=0; $i<2; $i++ ){
	$color = imagecolorallocate($this->mCheckImage, rand(0,255), rand(0,255), rand(0,255));
	$x1 = rand(0, $this->mCheckImageWidth/2);
	$y1 = rand(0, $this->mCheckImageHeight/2);
	$x2 = rand($this->mCheckImageWidth/2, $this->mCheckImageWidth);
	$y2 = rand($this->mCheckImageHeight/2, $this->mCheckImageHeight);
	imageline($this->mCheckImage, $x1, $y1, $x2, $y2, $color);
  }
 }
 /**
 *
 * @brief  set size
 *
 * @param  $width  width
 *
 * @param  $height height
 *
 */
function SetCheckImageWH($width,$height)
 {
  if($width==''||$height=='')return false;
  $this->mCheckImageWidth  = $width;
  $this->mCheckImageHeight = $height;
  return true;
 }
 /**
 * @brief  draw code
 */
function WriteCheckCodeToImage()
 {
  if( $this->mCheckMethod == 'string' ){
	$this ->CreateCheckCode();
	for ($i=0;$i<=$this->mCheckCodeNum;$i++)
	{
	 $bg_color = imagecolorallocate ($this->mCheckImage, rand(0,255), rand(0,128), rand(0,255));
	 $x = 3+floor($this->mCheckImageWidth/$this->mCheckCodeNum)*$i;
	 $y = rand(0,$this->mCheckImageHeight-15);
	 imagechar ($this->mCheckImage, 5, $x, $y, $this->mCheckCode[$i], $bg_color);
	}
  } else {
	$s = rand(0, 3);
	$towrite = "";
	switch($s){
	  case 0:
		$a = rand(0,20);
		$b = rand(0,20);
		$towrite = " $a + $b = ? ";
		$this->mCheckCode = $a + $b;
		break;
	  case 1:
		$a = rand(0,20);
		$c = rand(0,20);
		$b = $a+$c;
		$towrite = " $b - $a = ? ";
		$this->mCheckCode = $c;
		break;
	  case 2:
		$a = rand(0,10);
		$b = rand(0,10);
		$towrite = " $a * $b = ? ";
		$this->mCheckCode = $a * $b;
		break;
	  default:
		$a = rand(1,10);
		$b = rand(1,10);
		$c = $a*$b;
		$towrite = " $c / $a = ? ";
		$this->mCheckCode=$b;
		break;
	}
	for( $i=0; $i<strlen($towrite); $i++ ){
	  $bg_color = imagecolorallocate ($this->mCheckImage, rand(0,255), rand(0,128), rand(0,255));
	  $x = 3+floor($this->mCheckImageWidth/strlen($towrite))*$i;
	  $y = rand(0,$this->mCheckImageHeight-15);
	  imagechar ($this->mCheckImage, 5, $x, $y, $towrite[$i], $bg_color);
	}
  }
 }
 /**
 * @brief  output image
 */
function OutCheckImage()
 {
  $this ->OutFileHeader();
  //$this ->CreateCheckCode();
  //setcookie("vcode",md5($this->mCheckCode),time()+1800,'/');
  
  $this ->CreateImage();
  $this ->SetDisturbColor();
  $this ->WriteCheckCodeToImage();
  $_SESSION['vcode'] = md5($this->mCheckCode);
  imagepng($this->mCheckImage);
  imagedestroy($this->mCheckImage);
 }
}
$c_check_code_image = new CCheckCodeFile();
//$c_check_code_image ->SetCheckImageWH(100,50);
$c_check_code_image ->OutCheckImage();
?> 
