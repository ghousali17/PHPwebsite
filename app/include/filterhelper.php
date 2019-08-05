<?php


function execute($command)
    {
        # remove newlines and convert single quotes to double to prevent errors
        $command = str_replace(array("\n", "'"), array('', '"'), $command);
        $command = escapeshellcmd($command);
        # execute convert program
        exec($command);
    }
     
function border($input,$output, $color = 'black', $width = 20)
    {
        execute("convert $input -bordercolor $color -border {$width}x{$width} $output");
    }

function blur($input, $output, $radius = 10, $sigma= 5)
    {   $temp = $radius* $sigma;
        execute("convert $input -blur $temp $output");
    }

 function lomo($input,$output)
    {
        # copy original file and assign temporary name
        $command = "convert $input -channel R -level 33% -channel G -level 33% $output";
        execute($command);
        vignette($output);
        
    }
function vignette($input, $color_1 = 'none', $color_2 = 'black', $crop_factor = 1.5)
    {   list($width,$height) = getimagesize($input);
        $crop_x = floor($width * $crop_factor);
        $crop_y = floor($height * $crop_factor);
         
        execute("convert 
        ( {$input} ) 
        ( -size {$crop_x}x{$crop_y} 
        radial-gradient:$color_1-$color_2
        -gravity center -crop {$width}x{$height}+0+0 +repage )
        -compose multiply -flatten $input");   
    }
function lensflare($input,$output){
  list($width,$height) = getimagesize($input);
  $temp = $width * $height;
  $cmd_1 = "convert '../tools/lensflare.png' -resize $width '../tools/tmp.png'";
  execute($cmd_1);
  $cmd_2 = "composite -compose screen -gravity northwest ../tools/tmp.png  $input $output";
  execute($cmd_2);
  $cmd_3 = "rm ../tools/tmp.png";
  execute($cmd_3);
}
function blackwhite($input,$output)
{ 
  list($width,$height) = getimagesize($input);
  $temp = $width * $height;
  
  $cmd_1 = "convert $input -type grayscale $output";
  execute($cmd_1);
  $cmd_2= "convert ../tools/bwgrad.png -resize $temp '\! ../tools/tmp.png";
  execute($cmd_2);
  $cmd_3 = "composite -compose softlight -gravity center ../tools/tmp.png $output $output";
  execute($cmd_3);
  
}

function getNextPath($tempDestination, $currentversion){
  $output = "";
  $tempArray = explode('=', basename($tempDestination));
  if($tempArray['0'] == 'version'){
      $filename = $tempArray['2'];
      $version = $tempArray['1'];
      $version++;
      $output = dirname($tempDestination).'/version='.$version.'='.$filename;
  }else{

    $output =dirname($tempDestination).'/version=1'.'='.basename($tempDestination);
  }
return $output;
}

function getPreviousPath($tempDestination){
  $tempArray = explode('=', basename($tempDestination));
  $output = "";
  if($tempArray['0'] == 'version'){
      $filename = $tempArray['2'];
      $version = $tempArray['1'];
      if($version == 1)
      {
        $output= dirname($tempDestination).'/'.$filename;
  
      }else
      {
        $version--;
        $output = dirname($tempDestination).'/version='.$version.'='.$filename;
      }
      
  }else{

    $output = dirname($tempDestination).'/'.basename($tempDestination);
 
  }
return $output;

}
































?>