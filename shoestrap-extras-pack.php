<?php

/*
Plugin Name: Shoestrap Extras Pack
Plugin URI: http://wpmu.io
Description: Shoestrap Customizations and additions
Version: 0.4
Author: Aristeides Stathopoulos
Author URI:  http://aristeides.com
*/


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define the main file path
define ( 'SSEP_FILE_FATH', __FILE__ );

/**
* 
*/
class Shoestrap_Extras_Pack {

	function __construct() {

		add_filter( 'redux/options/shoestrap/sections', array( $this, 'options' ) );
		add_action( 'init', array( $this, 'init_meta_boxes' ), 9999 );
		add_action( 'shoestrap_include_files', array( $this, 'include_files' ) );
		add_filter( 'cmb_meta_boxes', array( $this, 'metabox' ) );

	}

	/*
	 * The admin options
	 */
	function options( $sections ) {
		global $redux;

		// Blog Options
		$section = array(
			'title' => __( 'Extras Pack', 'shoestrap' ),
			'icon'  => 'el-icon-plus',
		);

		$fields[] = array(
			'id'       => 'ssep_active_modules',
			'type'     => 'checkbox',
			'title'    => __( 'Activate extra modules', 'shoestrap' ),
			'subtitle' => '',
			'desc'     => '',

			'options'  => array(
				'hide_meta'  => __( 'Hide meta per post', 'shoestrap' ),
				'hide_title' => __( 'Hide titles per post', 'shoestrap' ),
				'jumbotrons' => __( 'Jombotron post type + assign per post', 'shoestrap' ),
				'layouts'    => __( 'Per-Post Layouts', 'shoestrap' ),
			),
			'default' => array(
				'hide_meta'  => '0',
				'hide_title' => '0',
				'jumbotrons' => '0',
				'layouts'    => '0'
			)
		);

		$section['fields'] = $fields;

		$sections[] = $section;
		return $sections;

	}

	/**
	 * Include files
	 */
	function include_files() {
		global $ss_settings;

		$ssep_active_modules = $ss_settings['ssep_active_modules'];

		if ( isset( $ssep_active_modules['hide_meta'] ) && '1' == $ssep_active_modules['hide_meta'] ) {
			// Hide meta info per-post
			require_once( plugin_dir_path(__FILE__) . 'includes/hide-meta.php' );
		}

		if ( isset( $ssep_active_modules['hide_title'] ) && '1' == $ssep_active_modules['hide_title'] ) {
			// Hide the title.
			require_once( plugin_dir_path(__FILE__) . 'includes/hide-title.php' );
		}

		if ( isset( $ssep_active_modules['jumbotrons'] ) && '1' == $ssep_active_modules['jumbotrons'] ) {
			// Add the Jumbotron Custom post type + fields
			require_once( plugin_dir_path(__FILE__) . 'includes/jumbotron.php' );
		}

		if ( isset( $ssep_active_modules['layouts'] ) && '1' == $ssep_active_modules['layouts'] ) {
			// Add Custom layouts per post
			require_once( plugin_dir_path(__FILE__) . 'includes/layouts.php' );
		}

	}

	/**
	 * Initialize the metabox class.
	 */
	function init_meta_boxes() {

		if ( ! class_exists( 'cmb_Meta_Box' ) )
			require_once( plugin_dir_path(__FILE__) . 'includes/cmf/init.php' );

	}

	/**
	 * Define the metabox
	 */
	function metabox( array $meta_boxes ) {

		// Get an array of the available post types
		$post_types = get_post_types( array( 'public' => true ), 'objects' );

		$pt_array = array();

		foreach ( $post_types as $post_type ) {

			if ( 'ss_jumbotron' != $post_type->name ) {
				$pt_array[] = $post_type->name;
			}

		}

		$meta_boxes[] = array(
			'id'         => 'ssep_metabox',
			'title'      => 'Shoestrap Extras',
			'pages'      => $pt_array,
			'context'    => 'normal',
			'priority'   => 'high',
			'show_names' => true, // Show field names on the left
			'fields'     => apply_filters( 'ssep_metabox_fields', array() ),
		);

		return $meta_boxes;
	}
}

$ssep = new Shoestrap_Extras_Pack();