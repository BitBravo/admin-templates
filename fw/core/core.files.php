<?php
/**
 * Cars4Rent Framework: file system manipulations, styles and scripts usage, etc.
 *
 * @package	cars4rent
 * @since	cars4rent 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* File names manipulations
------------------------------------------------------------------------------------- */

// Return path to directory with uploaded images
if (!function_exists('cars4rent_get_uploads_dir_from_url')) {	
	function cars4rent_get_uploads_dir_from_url($url) {
		$upload_info = wp_upload_dir();
		$upload_dir = $upload_info['basedir'];
		$upload_url = $upload_info['baseurl'];
		
		$http_prefix = "http://";
		$https_prefix = "https://";
		
		if (!strncmp($url, $https_prefix, cars4rent_strlen($https_prefix)))			//if url begins with https:// make $upload_url begin with https:// as well
			$upload_url = str_replace($http_prefix, $https_prefix, $upload_url);
		else if (!strncmp($url, $http_prefix, cars4rent_strlen($http_prefix)))		//if url begins with http:// make $upload_url begin with http:// as well
			$upload_url = str_replace($https_prefix, $http_prefix, $upload_url);		
	
		// Check if $img_url is local.
		if ( false === cars4rent_strpos( $url, $upload_url ) ) return false;
	
		// Define path of image.
		$rel_path = str_replace( $upload_url, '', $url );
		$img_path = ($upload_dir) . ($rel_path);
		
		return $img_path;
	}
}

// Replace uploads url to current site uploads url
if (!function_exists('cars4rent_replace_uploads_url')) {	
	function cars4rent_replace_uploads_url($str, $uploads_folder='uploads') {
		static $uploads_url = '', $uploads_len = 0;
		if (is_array($str) && count($str) > 0) {
			foreach ($str as $k=>$v) {
				$str[$k] = cars4rent_replace_uploads_url($v, $uploads_folder);
			}
		} else if (is_string($str)) {
			if (empty($uploads_url)) {
				$uploads_info = wp_upload_dir();
				$uploads_url = $uploads_info['baseurl'];
				$uploads_len = cars4rent_strlen($uploads_url);
			}
			$break = '\'" ';
			$pos = 0;
			while (($pos = cars4rent_strpos($str, "/{$uploads_folder}/", $pos))!==false) {
				$pos0 = $pos;
				$chg = true;
				while ($pos0) {
					if (cars4rent_strpos($break, cars4rent_substr($str, $pos0, 1))!==false) {
						$chg = false;
						break;
					}
					if (cars4rent_substr($str, $pos0, 5)=='http:' || cars4rent_substr($str, $pos0, 6)=='https:')
						break;
					$pos0--;
				}
				if ($chg) {
					$str = ($pos0 > 0 ? cars4rent_substr($str, 0, $pos0) : '') . ($uploads_url) . cars4rent_substr($str, $pos+cars4rent_strlen($uploads_folder)+1);
					$pos = $pos0 + $uploads_len;
				} else 
					$pos++;
			}
		}
		return $str;
	}
}

// Replace site url to current site url
if (!function_exists('cars4rent_replace_site_url')) {	
	function cars4rent_replace_site_url($str, $old_url) {
		static $site_url = '', $site_len = 0;
		if (is_array($str) && count($str) > 0) {
			foreach ($str as $k=>$v) {
				$str[$k] = cars4rent_replace_site_url($v, $old_url);
			}
		} else if (is_string($str)) {
			if (empty($site_url)) {
				$site_url = get_site_url();
				$site_len = cars4rent_strlen($site_url);
				if (cars4rent_substr($site_url, -1)=='/') {
					$site_len--;
					$site_url = cars4rent_substr($site_url, 0, $site_len);
				}
			}
			if (cars4rent_substr($old_url, -1)=='/') $old_url = cars4rent_substr($old_url, 0, cars4rent_strlen($old_url)-1);
			$break = '\'" ';
			$pos = 0;
			while (($pos = cars4rent_strpos($str, $old_url, $pos))!==false) {
				$str = cars4rent_unserialize($str);
				if (is_array($str) && count($str) > 0) {
					foreach ($str as $k=>$v) {
						$str[$k] = cars4rent_replace_site_url($v, $old_url);
					}
					$str = serialize($str);
					break;
				} else {
					$pos0 = $pos;
					$chg = true;
					while ($pos0 >= 0) {
						if (cars4rent_strpos($break, cars4rent_substr($str, $pos0, 1))!==false) {
							$chg = false;
							break;
						}
						if (cars4rent_substr($str, $pos0, 5)=='http:' || cars4rent_substr($str, $pos0, 6)=='https:')
							break;
						$pos0--;
					}
					if ($chg && $pos0>=0) {
						$str = ($pos0 > 0 ? cars4rent_substr($str, 0, $pos0) : '') . ($site_url) . cars4rent_substr($str, $pos+cars4rent_strlen($old_url));
						$pos = $pos0 + $site_len;
					} else 
						$pos++;
				}
			}
		}
		return $str;
	}
}

// Get domain part from URL
if (!function_exists('cars4rent_get_domain_from_url')) {
	function cars4rent_get_domain_from_url($url) {
		if (($pos=strpos($url, '://'))!==false) $url = substr($url, $pos+3);
		if (($pos=strpos($url, '/'))!==false) $url = substr($url, 0, $pos);
		return $url;
	}
}

// Return file extension from full name/path
if (!function_exists('cars4rent_get_file_ext')) {
	function cars4rent_get_file_ext($file) {
		$parts = pathinfo($file);
		return $parts['extension'];
	}
}



/* File system utils
------------------------------------------------------------------------------------- */

// Init WP Filesystem
if (!function_exists('cars4rent_init_filesystem')) {
	add_action( 'after_setup_theme', 'cars4rent_init_filesystem', 0);
	function cars4rent_init_filesystem() {
        if( !function_exists('WP_Filesystem') ) {
            require_once( ABSPATH .'/wp-admin/includes/file.php' );
        }
		if (is_admin()) {
			$url = admin_url();
			$creds = false;
			// First attempt to get credentials.
			if ( function_exists('request_filesystem_credentials') && false === ( $creds = request_filesystem_credentials( $url, '', false, false, array() ) ) ) {
				// If we comes here - we don't have credentials
				// so the request for them is displaying no need for further processing
				return false;
			}

			// Now we got some credentials - try to use them.
			if ( !WP_Filesystem( $creds ) ) {
				// Incorrect connection data - ask for credentials again, now with error message.
				if ( function_exists('request_filesystem_credentials') ) request_filesystem_credentials( $url, '', true, false );
				return false;
			}

			return true; // Filesystem object successfully initiated.
		} else {
            WP_Filesystem();
		}
		return true;
	}
}


// Put data into specified file
if (!function_exists('cars4rent_fpc')) {	
	function cars4rent_fpc($file, $data, $flag=0) {
		global $wp_filesystem;
		if (!empty($file)) {
			if (isset($wp_filesystem) && is_object($wp_filesystem)) {
				$file = str_replace(ABSPATH, $wp_filesystem->abspath(), $file);
				// Attention! WP_Filesystem can't append the content to the file!
				// That's why we have to read the contents of the file into a string,
				// add new content to this string and re-write it to the file if parameter $flag == FILE_APPEND!
				return $wp_filesystem->put_contents($file, ($flag==FILE_APPEND ? $wp_filesystem->get_contents($file) : '') . $data, false);
			} else {
				if (cars4rent_param_is_on(cars4rent_get_theme_option('debug_mode')))
					throw new Exception(sprintf(esc_html__('WP Filesystem is not initialized! Put contents to the file "%s" failed', 'cars4rent'), $file));
			}
		}
		return false;
	}
}

// Get text from specified file
if (!function_exists('cars4rent_fgc')) {	
	function cars4rent_fgc($file) {
		global $wp_filesystem;
		if (!empty($file)) {
			if (isset($wp_filesystem) && is_object($wp_filesystem)) {
				$file = str_replace(ABSPATH, $wp_filesystem->abspath(), $file);
				return $wp_filesystem->get_contents($file);
			} else {
				if (cars4rent_param_is_on(cars4rent_get_theme_option('debug_mode')))
					throw new Exception(sprintf(esc_html__('WP Filesystem is not initialized! Get contents from the file "%s" failed', 'cars4rent'), $file));
			}
		}
		return '';
	}
}

// Get array with rows from specified file
if (!function_exists('cars4rent_fga')) {	
	function cars4rent_fga($file) {
		global $wp_filesystem;
		if (!empty($file)) {
			if (isset($wp_filesystem) && is_object($wp_filesystem)) {
				$file = str_replace(ABSPATH, $wp_filesystem->abspath(), $file);
				return $wp_filesystem->get_contents_array($file);
			} else {
				if (cars4rent_param_is_on(cars4rent_get_theme_option('debug_mode')))
					throw new Exception(sprintf(esc_html__('WP Filesystem is not initialized! Get rows from the file "%s" failed', 'cars4rent'), $file));
			}
		}
		return array();
	}
}

// Remove unsafe characters from file/folder path
if (!function_exists('cars4rent_esc')) {	
	function cars4rent_esc($file) {
		return sanitize_file_name($file);
	}
}


/* Check if file/folder present in the child theme and return path (url) to it.
   Else - path (url) to file in the main theme dir
------------------------------------------------------------------------------------- */

// Detect file location with next algorithm:
// 1) check in the child theme folder
// 2) check in the framework folder in the child theme folder
// 3) check in the main theme folder
// 4) check in the framework folder in the main theme folder
if (!function_exists('cars4rent_get_file_dir')) {	
	function cars4rent_get_file_dir($file, $return_url=false) {
		if ($file[0]=='/') $file = cars4rent_substr($file, 1);
		$theme_dir = get_template_directory();
		$theme_url = get_template_directory_uri();
		$child_dir = get_stylesheet_directory();
		$child_url = get_stylesheet_directory_uri();
		$dir = '';
		if (file_exists(($child_dir).'/'.($file)))
			$dir = ($return_url ? $child_url : $child_dir).'/'.($file);
		else if (file_exists(($child_dir).'/'.(CARS4RENT_FW_DIR).'/'.($file)))
			$dir = ($return_url ? $child_url : $child_dir).'/'.(CARS4RENT_FW_DIR).'/'.($file);
		else if (file_exists(($theme_dir).'/'.($file)))
			$dir = ($return_url ? $theme_url : $theme_dir).'/'.($file);
		else if (file_exists(($theme_dir).'/'.(CARS4RENT_FW_DIR).'/'.($file)))
			$dir = ($return_url ? $theme_url : $theme_dir).'/'.(CARS4RENT_FW_DIR).'/'.($file);
		return $dir;
	}
}

// Detect file location with next algorithm:
// 1) check in the main theme folder
// 2) check in the framework folder in the main theme folder
// and return file slug (relative path to the file without extension)
// to use it in the get_template_part()
if (!function_exists('cars4rent_get_file_slug')) {	
	function cars4rent_get_file_slug($file) {
		if ($file[0]=='/') $file = cars4rent_substr($file, 1);
		$theme_dir = get_template_directory();
		$dir = '';
		if (file_exists(($theme_dir).'/'.($file)))
			$dir = $file;
		else if (file_exists(($theme_dir).'/'.CARS4RENT_FW_DIR.'/'.($file)))
			$dir = CARS4RENT_FW_DIR.'/'.($file);
		if (cars4rent_substr($dir, -4)=='.php') $dir = cars4rent_substr($dir, 0, cars4rent_strlen($dir)-4);
		return $dir;
	}
}

if (!function_exists('cars4rent_get_file_url')) {	
	function cars4rent_get_file_url($file) {
		return cars4rent_get_file_dir($file, true);
	}
}

// Detect folder location with same algorithm as file (see above)
if (!function_exists('cars4rent_get_folder_dir')) {	
	function cars4rent_get_folder_dir($folder, $return_url=false) {
		if ($folder[0]=='/') $folder = cars4rent_substr($folder, 1);
		$theme_dir = get_template_directory();
		$theme_url = get_template_directory_uri();
		$child_dir = get_stylesheet_directory();
		$child_url = get_stylesheet_directory_uri();
		$dir = '';
		if (is_dir(($child_dir).'/'.($folder)))
			$dir = ($return_url ? $child_url : $child_dir).'/'.($folder);
		else if (is_dir(($child_dir).'/'.(CARS4RENT_FW_DIR).'/'.($folder)))
			$dir = ($return_url ? $child_url : $child_dir).'/'.(CARS4RENT_FW_DIR).'/'.($folder);
		else if (file_exists(($theme_dir).'/'.($folder)))
			$dir = ($return_url ? $theme_url : $theme_dir).'/'.($folder);
		else if (file_exists(($theme_dir).'/'.(CARS4RENT_FW_DIR).'/'.($folder)))
			$dir = ($return_url ? $theme_url : $theme_dir).'/'.(CARS4RENT_FW_DIR).'/'.($folder);
		return $dir;
	}
}

if (!function_exists('cars4rent_get_folder_url')) {	
	function cars4rent_get_folder_url($folder) {
		return cars4rent_get_folder_dir($folder, true);
	}
}

// Return path to social icon (if exists)
if (!function_exists('cars4rent_get_socials_dir')) {	
	function cars4rent_get_socials_dir($soc, $return_url=false) {
		return cars4rent_get_file_dir('images/socials/' . cars4rent_esc($soc) . (cars4rent_strpos($soc, '.')===false ? '.png' : ''), $return_url, true);
	}
}

if (!function_exists('cars4rent_get_socials_url')) {	
	function cars4rent_get_socials_url($soc) {
		return cars4rent_get_socials_dir($soc, true);
	}
}

// Detect theme version of the template (if exists), else return it from fw templates directory
if (!function_exists('cars4rent_get_template_dir')) {	
	function cars4rent_get_template_dir($tpl) {
		return cars4rent_get_file_dir('templates/' . cars4rent_esc($tpl) . (cars4rent_strpos($tpl, '.php')===false ? '.php' : ''));
	}
}
?>