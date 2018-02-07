<?php
function read_all_file($url){
	$encode = 'utf-8';
	$url = '';
	$format = 'phptag';
	$format_array = array();
	$temp_array = array();
	$status = 'lock';
	$cache = '';
	$handle = fopen ($url,"rb") or exit("Unable to open file!");
	while(!feof($handle)){
		$data = fgetc($handle);
		if(($data != "\t")&&($data != "\r")&&($data != "\n")&&($data != "\r\n")){
			if($data == '<'){
				$status = 'unlock';
				if(isset($temp_array[0])){
					array_push($format_array,implode('',$temp_array));
					unset($temp_array);
				}
				$temp_array = array('<');
			}
			else if($data == '>'){
				$status = 'lock';
				array_push($temp_array,'>');
				array_push($format_array,implode('',$temp_array));
				unset($temp_array);
				$temp_array = array();
			}
			else{
				if($status == 'lock'){
					if($data == ' '){
						$data = '';
					}
					else{
						array_push($temp_array,$data);
					}
				}
				else if($status == 'unlock'){
					array_push($temp_array,$data);
				}
			}
		}
	}
	fclose($handle);
	return $format_array;
}