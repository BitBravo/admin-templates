<?php
/**
 * Cars4Rent Framework: ini-files manipulations
 *
 * @package	cars4rent
 * @since	cars4rent 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


//  Get value by name from .ini-file
if (!function_exists('cars4rent_ini_get_value')) {
	function cars4rent_ini_get_value($file, $name, $defa='') {
		if (!is_array($file)) {
			if (file_exists($file)) {
				$file = cars4rent_fga($file);
			} else
				return $defa;
		}
		$name = cars4rent_strtolower($name);
		$rez = $defa;
		for ($i=0; $i<count($file); $i++) {
			$file[$i] = trim($file[$i]);
			if (($pos = cars4rent_strpos($file[$i], ';'))!==false)
				$file[$i] = trim(cars4rent_substr($file[$i], 0, $pos));
			$parts = explode('=', $file[$i]);
			if (count($parts)!=2) continue;
			if (cars4rent_strtolower(trim(chop($parts[0])))==$name) {
				$rez = trim(chop($parts[1]));
				if (cars4rent_substr($rez, 0, 1)=='"')
					$rez = cars4rent_substr($rez, 1, cars4rent_strlen($rez)-2);
				else
					$rez *= 1;
				break;
			}
		}
		return $rez;
	}
}

//  Retrieve all values from .ini-file as assoc array
if (!function_exists('cars4rent_ini_get_values')) {
	function cars4rent_ini_get_values($file) {
		$rez = array();
		if (!is_array($file)) {
			if (file_exists($file)) {
				$file = cars4rent_fga($file);
			} else
				return $rez;
		}
		for ($i=0; $i<count($file); $i++) {
			$file[$i] = trim(chop($file[$i]));
			if (($pos = cars4rent_strpos($file[$i], ';'))!==false)
				$file[$i] = trim(cars4rent_substr($file[$i], 0, $pos));
			$parts = explode('=', $file[$i]);
			if (count($parts)!=2) continue;
			$key = trim(chop($parts[0]));
			$rez[$key] = trim($parts[1]);
			if (cars4rent_substr($rez[$key], 0, 1)=='"')
				$rez[$key] = cars4rent_substr($rez[$key], 1, cars4rent_strlen($rez[$key])-2);
			else
				$rez[$key] *= 1;
		}
		return $rez;
	}
}
?>