<?php
/**
 * Cars4Rent Framework: theme variables storage
 *
 * @package	cars4rent
 * @since	cars4rent 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Get theme variable
if (!function_exists('cars4rent_storage_get')) {
	function cars4rent_storage_get($var_name, $default='') {
		global $CARS4RENT_STORAGE;
		return isset($CARS4RENT_STORAGE[$var_name]) ? $CARS4RENT_STORAGE[$var_name] : $default;
	}
}

// Set theme variable
if (!function_exists('cars4rent_storage_set')) {
	function cars4rent_storage_set($var_name, $value) {
		global $CARS4RENT_STORAGE;
		$CARS4RENT_STORAGE[$var_name] = $value;
	}
}

// Check if theme variable is empty
if (!function_exists('cars4rent_storage_empty')) {
	function cars4rent_storage_empty($var_name, $key='', $key2='') {
		global $CARS4RENT_STORAGE;
		if (!empty($key) && !empty($key2))
			return empty($CARS4RENT_STORAGE[$var_name][$key][$key2]);
		else if (!empty($key))
			return empty($CARS4RENT_STORAGE[$var_name][$key]);
		else
			return empty($CARS4RENT_STORAGE[$var_name]);
	}
}

// Check if theme variable is set
if (!function_exists('cars4rent_storage_isset')) {
	function cars4rent_storage_isset($var_name, $key='', $key2='') {
		global $CARS4RENT_STORAGE;
		if (!empty($key) && !empty($key2))
			return isset($CARS4RENT_STORAGE[$var_name][$key][$key2]);
		else if (!empty($key))
			return isset($CARS4RENT_STORAGE[$var_name][$key]);
		else
			return isset($CARS4RENT_STORAGE[$var_name]);
	}
}

// Inc/Dec theme variable with specified value
if (!function_exists('cars4rent_storage_inc')) {
	function cars4rent_storage_inc($var_name, $value=1) {
		global $CARS4RENT_STORAGE;
		if (empty($CARS4RENT_STORAGE[$var_name])) $CARS4RENT_STORAGE[$var_name] = 0;
		$CARS4RENT_STORAGE[$var_name] += $value;
	}
}

// Concatenate theme variable with specified value
if (!function_exists('cars4rent_storage_concat')) {
	function cars4rent_storage_concat($var_name, $value) {
		global $CARS4RENT_STORAGE;
		if (empty($CARS4RENT_STORAGE[$var_name])) $CARS4RENT_STORAGE[$var_name] = '';
		$CARS4RENT_STORAGE[$var_name] .= $value;
	}
}

// Get array (one or two dim) element
if (!function_exists('cars4rent_storage_get_array')) {
	function cars4rent_storage_get_array($var_name, $key, $key2='', $default='') {
		global $CARS4RENT_STORAGE;
		if (empty($key2))
			return !empty($var_name) && !empty($key) && isset($CARS4RENT_STORAGE[$var_name][$key]) ? $CARS4RENT_STORAGE[$var_name][$key] : $default;
		else
			return !empty($var_name) && !empty($key) && isset($CARS4RENT_STORAGE[$var_name][$key][$key2]) ? $CARS4RENT_STORAGE[$var_name][$key][$key2] : $default;
	}
}

// Set array element
if (!function_exists('cars4rent_storage_set_array')) {
	function cars4rent_storage_set_array($var_name, $key, $value) {
		global $CARS4RENT_STORAGE;
		if (!isset($CARS4RENT_STORAGE[$var_name])) $CARS4RENT_STORAGE[$var_name] = array();
		if ($key==='')
			$CARS4RENT_STORAGE[$var_name][] = $value;
		else
			$CARS4RENT_STORAGE[$var_name][$key] = $value;
	}
}

// Set two-dim array element
if (!function_exists('cars4rent_storage_set_array2')) {
	function cars4rent_storage_set_array2($var_name, $key, $key2, $value) {
		global $CARS4RENT_STORAGE;
		if (!isset($CARS4RENT_STORAGE[$var_name])) $CARS4RENT_STORAGE[$var_name] = array();
		if (!isset($CARS4RENT_STORAGE[$var_name][$key])) $CARS4RENT_STORAGE[$var_name][$key] = array();
		if ($key2==='')
			$CARS4RENT_STORAGE[$var_name][$key][] = $value;
		else
			$CARS4RENT_STORAGE[$var_name][$key][$key2] = $value;
	}
}

// Add array element after the key
if (!function_exists('cars4rent_storage_set_array_after')) {
	function cars4rent_storage_set_array_after($var_name, $after, $key, $value='') {
		global $CARS4RENT_STORAGE;
		if (!isset($CARS4RENT_STORAGE[$var_name])) $CARS4RENT_STORAGE[$var_name] = array();
		if (is_array($key))
			cars4rent_array_insert_after($CARS4RENT_STORAGE[$var_name], $after, $key);
		else
			cars4rent_array_insert_after($CARS4RENT_STORAGE[$var_name], $after, array($key=>$value));
	}
}

// Add array element before the key
if (!function_exists('cars4rent_storage_set_array_before')) {
	function cars4rent_storage_set_array_before($var_name, $before, $key, $value='') {
		global $CARS4RENT_STORAGE;
		if (!isset($CARS4RENT_STORAGE[$var_name])) $CARS4RENT_STORAGE[$var_name] = array();
		if (is_array($key))
			cars4rent_array_insert_before($CARS4RENT_STORAGE[$var_name], $before, $key);
		else
			cars4rent_array_insert_before($CARS4RENT_STORAGE[$var_name], $before, array($key=>$value));
	}
}

// Push element into array
if (!function_exists('cars4rent_storage_push_array')) {
	function cars4rent_storage_push_array($var_name, $key, $value) {
		global $CARS4RENT_STORAGE;
		if (!isset($CARS4RENT_STORAGE[$var_name])) $CARS4RENT_STORAGE[$var_name] = array();
		if ($key==='')
			array_push($CARS4RENT_STORAGE[$var_name], $value);
		else {
			if (!isset($CARS4RENT_STORAGE[$var_name][$key])) $CARS4RENT_STORAGE[$var_name][$key] = array();
			array_push($CARS4RENT_STORAGE[$var_name][$key], $value);
		}
	}
}

// Pop element from array
if (!function_exists('cars4rent_storage_pop_array')) {
	function cars4rent_storage_pop_array($var_name, $key='', $defa='') {
		global $CARS4RENT_STORAGE;
		$rez = $defa;
		if ($key==='') {
			if (isset($CARS4RENT_STORAGE[$var_name]) && is_array($CARS4RENT_STORAGE[$var_name]) && count($CARS4RENT_STORAGE[$var_name]) > 0) 
				$rez = array_pop($CARS4RENT_STORAGE[$var_name]);
		} else {
			if (isset($CARS4RENT_STORAGE[$var_name][$key]) && is_array($CARS4RENT_STORAGE[$var_name][$key]) && count($CARS4RENT_STORAGE[$var_name][$key]) > 0) 
				$rez = array_pop($CARS4RENT_STORAGE[$var_name][$key]);
		}
		return $rez;
	}
}

// Inc/Dec array element with specified value
if (!function_exists('cars4rent_storage_inc_array')) {
	function cars4rent_storage_inc_array($var_name, $key, $value=1) {
		global $CARS4RENT_STORAGE;
		if (!isset($CARS4RENT_STORAGE[$var_name])) $CARS4RENT_STORAGE[$var_name] = array();
		if (empty($CARS4RENT_STORAGE[$var_name][$key])) $CARS4RENT_STORAGE[$var_name][$key] = 0;
		$CARS4RENT_STORAGE[$var_name][$key] += $value;
	}
}

// Concatenate array element with specified value
if (!function_exists('cars4rent_storage_concat_array')) {
	function cars4rent_storage_concat_array($var_name, $key, $value) {
		global $CARS4RENT_STORAGE;
		if (!isset($CARS4RENT_STORAGE[$var_name])) $CARS4RENT_STORAGE[$var_name] = array();
		if (empty($CARS4RENT_STORAGE[$var_name][$key])) $CARS4RENT_STORAGE[$var_name][$key] = '';
		$CARS4RENT_STORAGE[$var_name][$key] .= $value;
	}
}

// Call object's method
if (!function_exists('cars4rent_storage_call_obj_method')) {
	function cars4rent_storage_call_obj_method($var_name, $method, $param=null) {
		global $CARS4RENT_STORAGE;
		if ($param===null)
			return !empty($var_name) && !empty($method) && isset($CARS4RENT_STORAGE[$var_name]) ? $CARS4RENT_STORAGE[$var_name]->$method(): '';
		else
			return !empty($var_name) && !empty($method) && isset($CARS4RENT_STORAGE[$var_name]) ? $CARS4RENT_STORAGE[$var_name]->$method($param): '';
	}
}

// Get object's property
if (!function_exists('cars4rent_storage_get_obj_property')) {
	function cars4rent_storage_get_obj_property($var_name, $prop, $default='') {
		global $CARS4RENT_STORAGE;
		return !empty($var_name) && !empty($prop) && isset($CARS4RENT_STORAGE[$var_name]->$prop) ? $CARS4RENT_STORAGE[$var_name]->$prop : $default;
	}
}
?>