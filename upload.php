<?php
/*
 Saving chunks of uploaded file with returning information about upload status
 Author: acapor
*/

if(isset($_POST['filename']))echo file_upload($_POST['filename']);
 else echo "Error - No file name!";

 function file_upload($filename){
	if(!isset($_POST['data']))return "Error - data from file are absent";
	
	$loaded = 0; $data = $_POST['data'];
	$f_path = "files/$filename";
	if(isset($_POST['loaded']))$loaded = intval($_POST['loaded']);
 	
    if(!$loaded){ $f1 = fopen($f_path,"wb"); fclose($f1);}
    $f1 = fopen($f_path,"a+");  fwrite($f1,base64_decode(str_replace("data:application/octet-stream;base64,","",$data)));  fclose($f1);
   
    return json_encode(array(
     "len" => strlen($data), 
     "fname"=>$filename, 
     "ld"=>$loaded
    ));
}
 