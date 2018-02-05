<?php
function read_all_file($argument){
	$encode = 'utf-8';
	$url = '';
	$format = 'phptag';
	$argument_array = explode(":",$argument);
	unset($argument);
	$argument_array_count = 0;
	while(isset($argument_array[$argument_array_count])){
		switch($argument_array[$argument_array_count]){
			case 'url':
				++$argument_array_count;
				if('http' == $argument_array[$argument_array_count]){
					$url = $argument_array[$argument_array_count].':'.$argument_array[$argument_array_count+1];
					$argument_array_count += 2;
				}
				else{
					$url = $argument_array[$argument_array_count];
					++$argument_array_count;
				}
				break;
			case 'encode':
				++$argument_array_count;
				$encode = $argument_array[$argument_array_count];
				++$argument_array_count;
				break;
			case 'format':
				++$argument_array_count;
				$format = $argument_array[$argument_array_count];
				++$argument_array_count;
				break;
			default:
		}
		++$argument_array_count;
	}
	unset($argument_array,$argument_array_count);
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