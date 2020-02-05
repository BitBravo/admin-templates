<?php
/**
 * Cars4Rent Framework: strings manipulations
 *
 * @package	cars4rent
 * @since	cars4rent 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Check multibyte functions
if ( ! defined( 'CARS4RENT_MULTIBYTE' ) ) define( 'CARS4RENT_MULTIBYTE', function_exists('mb_strpos') ? 'UTF-8' : false );

if (!function_exists('cars4rent_strlen')) {
	function cars4rent_strlen($text) {
		return CARS4RENT_MULTIBYTE ? mb_strlen($text) : strlen($text);
	}
}

if (!function_exists('cars4rent_strpos')) {
	function cars4rent_strpos($text, $char, $from=0) {
		return CARS4RENT_MULTIBYTE ? mb_strpos($text, $char, $from) : strpos($text, $char, $from);
	}
}

if (!function_exists('cars4rent_strrpos')) {
	function cars4rent_strrpos($text, $char, $from=0) {
		return CARS4RENT_MULTIBYTE ? mb_strrpos($text, $char, $from) : strrpos($text, $char, $from);
	}
}

if (!function_exists('cars4rent_substr')) {
	function cars4rent_substr($text, $from, $len=-999999) {
		if ($len==-999999) { 
			if ($from < 0)
				$len = -$from; 
			else
				$len = cars4rent_strlen($text)-$from;
		}
		return CARS4RENT_MULTIBYTE ? mb_substr($text, $from, $len) : substr($text, $from, $len);
	}
}

if (!function_exists('cars4rent_strtolower')) {
	function cars4rent_strtolower($text) {
		return CARS4RENT_MULTIBYTE ? mb_strtolower($text) : strtolower($text);
	}
}

if (!function_exists('cars4rent_strtoupper')) {
	function cars4rent_strtoupper($text) {
		return CARS4RENT_MULTIBYTE ? mb_strtoupper($text) : strtoupper($text);
	}
}

if (!function_exists('cars4rent_strtoproper')) {
	function cars4rent_strtoproper($text) { 
		$rez = ''; $last = ' ';
		for ($i=0; $i<cars4rent_strlen($text); $i++) {
			$ch = cars4rent_substr($text, $i, 1);
			$rez .= cars4rent_strpos(' .,:;?!()[]{}+=', $last)!==false ? cars4rent_strtoupper($ch) : cars4rent_strtolower($ch);
			$last = $ch;
		}
		return $rez;
	}
}

if (!function_exists('cars4rent_strrepeat')) {
	function cars4rent_strrepeat($str, $n) {
		$rez = '';
		for ($i=0; $i<$n; $i++)
			$rez .= $str;
		return $rez;
	}
}

if (!function_exists('cars4rent_strshort')) {
	function cars4rent_strshort($str, $maxlength, $add='...') {
		if ($maxlength < 0) 
			return $str;
		if ($maxlength == 0) 
			return '';
		if ($maxlength >= cars4rent_strlen($str)) 
			return strip_tags($str);
		$str = cars4rent_substr(strip_tags($str), 0, $maxlength - cars4rent_strlen($add));
		$ch = cars4rent_substr($str, $maxlength - cars4rent_strlen($add), 1);
		if ($ch != ' ') {
			for ($i = cars4rent_strlen($str) - 1; $i > 0; $i--)
				if (cars4rent_substr($str, $i, 1) == ' ') break;
			$str = trim(cars4rent_substr($str, 0, $i));
		}
		if (!empty($str) && cars4rent_strpos(',.:;-', cars4rent_substr($str, -1))!==false) $str = cars4rent_substr($str, 0, -1);
		return ($str) . ($add);
	}
}

// Clear string from spaces, line breaks and tags (only around text)
if (!function_exists('cars4rent_strclear')) {
	function cars4rent_strclear($text, $tags=array()) {
		if (empty($text)) return $text;
		if (!is_array($tags)) {
			if ($tags != '')
				$tags = explode($tags, ',');
			else
				$tags = array();
		}
		$text = trim(chop($text));
		if (is_array($tags) && count($tags) > 0) {
			foreach ($tags as $tag) {
				$open  = '<'.esc_attr($tag);
				$close = '</'.esc_attr($tag).'>';
				if (cars4rent_substr($text, 0, cars4rent_strlen($open))==$open) {
					$pos = cars4rent_strpos($text, '>');
					if ($pos!==false) $text = cars4rent_substr($text, $pos+1);
				}
				if (cars4rent_substr($text, -cars4rent_strlen($close))==$close) $text = cars4rent_substr($text, 0, cars4rent_strlen($text) - cars4rent_strlen($close));
				$text = trim(chop($text));
			}
		}
		return $text;
	}
}

// Return slug for the any title string
if (!function_exists('cars4rent_get_slug')) {
	function cars4rent_get_slug($title) {
		return cars4rent_strtolower(str_replace(array('\\','/','-',' ','.'), '_', $title));
	}
}

// Replace macros in the string
if (!function_exists('cars4rent_strmacros')) {
	function cars4rent_strmacros($str) {
		return str_replace(array("{{", "}}", "((", "))", "||"), array("<i>", "</i>", "<b>", "</b>", "<br>"), $str);
	}
}

// Unserialize string (try replace \n with \r\n)
if (!function_exists('cars4rent_unserialize')) {
	function cars4rent_unserialize($str) {
		if ( is_serialized($str) ) {
			try {
				$data = unserialize($str);
			} catch (Exception $e) {
				dcl($e->getMessage());
				$data = false;
			}
			if ($data===false) {
				try {
					$data = unserialize(str_replace("\n", "\r\n", $str));
				} catch (Exception $e) {
					dcl($e->getMessage());
					$data = false;
				}
			}
			return $data;
		} else
			return $str;
	}
}
?>