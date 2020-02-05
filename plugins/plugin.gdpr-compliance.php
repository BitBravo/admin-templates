<?php
/* The GDPR compliance support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('cars4rent_gdpr_compliance_theme_setup')) {
    add_action( 'cars4rent_action_before_init_theme', 'cars4rent_gdpr_compliance_theme_setup', 1 );
    function cars4rent_gdpr_compliance_theme_setup() {
        if (is_admin()) {
            add_filter( 'cars4rent_filter_required_plugins', 'cars4rent_gdpr_compliance_required_plugins' );
        }
    }
}

// Check if Instagram Widget installed and activated
if ( !function_exists( 'cars4rent_exists_gdpr_compliance' ) ) {
    function cars4rent_exists_gdpr_compliance() {
        return defined( 'WP_GDPR_C_SLUG' );
    }
}

// Filter to add in the required plugins list
if ( !function_exists( 'cars4rent_gdpr_compliance_required_plugins' ) ) {
    //Handler of add_filter('cars4rent_filter_required_plugins',    'cars4rent_gdpr_compliance_required_plugins');
    function cars4rent_gdpr_compliance_required_plugins($list=array()) {
        if (in_array('wp-gdpr-compliance', (array)cars4rent_storage_get('required_plugins')))
            $list[] = array(
                    'name'         => esc_html__('WP GDPR Compliance', 'cars4rent'),
                    'slug'         => 'wp-gdpr-compliance',
                    'required'     => false
                );
        return $list;
    }
}

