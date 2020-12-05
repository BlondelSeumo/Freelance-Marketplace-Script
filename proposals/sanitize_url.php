<?php

	function proposalUrl($string, $space="-"){
	   
		if(preg_match('/[اأإء-ي]/ui', $string)){
			return urlencode($string);
		}else{

			if(preg_match( '/[\p{Cyrillic}]/u', $string)){
				$string = str_replace(" ","-", $string);
				return urlencode($string);
			}else{

				$turkcefrom = array("/Ğ/","/Ü/","/Ş/","/İ/","/Ö/","/Ç/","/ğ/","/ü/","/ş/","/ı/","/ö/","/ç/");
				$turkceto = array("G","U","S","I","O","C","g","u","s","i","o","c");

				$string = utf8_encode($string);
				if(function_exists('iconv')) {
				  // $string = iconv('UTF-8', 'ASCII//TRANSLIT', mb_strtolower($string));
				}

				$string = preg_replace("/[^a-zA-Z0-9 \-]/", "", $string);
				$string = trim(preg_replace("/\\s+/", " ", $string));
				$string = strtolower($string);
				$string = str_replace(" ", $space, $string);

				$string = preg_replace("/[^0-9a-zA-ZÄzÜŞİÖÇğüşıöç]/"," ",$string);
				$string = preg_replace($turkcefrom,$turkceto,$string);
				$string = preg_replace("/ +/"," ",$string);
				$string = preg_replace("/ /","-",$string);
				$string = preg_replace("/\s/","",$string);
				$string = strtolower($string);
				$string = preg_replace("/^-/","",$string);
				$string = preg_replace("/-$/","",$string);
				return $string;

			}

		}
	  
	}