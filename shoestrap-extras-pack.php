<?php

/*
Plugin Name: Shoestrap Extras Pack
Plugin URI: http://wpmu.io
Description: Shoestrap Customizations and additions
Version: 0.6
Author: Aristeides Stathopoulos
Author URI:  http://aristeides.com
*/


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define the main file path
define ( 'SSEP_FILE_FATH', __FILE__ );

// Define the plugin version
define ( 'SSEP_PLUGIN_VER', '0.6' );

// Include the updater
require_once( plugin_dir_path(__FILE__) . 'includes/updater/updater.php' );

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
				'hidemeta'   => __( 'Hide meta per post', 'shoestrap' ),
				'hidetitle'  => __( 'Hide titles per post', 'shoestrap' ),
				'jumbotrons' => __( 'Jombotron post type + per post assignments', 'shoestrap' ),
				'layouts'    => __( 'Per-Post Layouts', 'shoestrap' ),
				'featured'   => __( 'Featured Content Section', 'shoestrap' )
			),
			'default' => array(
				'hidemeta'   => '0',
				'hidetitle'  => '0',
				'jumbotrons' => '0',
				'layouts'    => '0',
				'featured'   => '0'
			)
		);

		$fields[] = array(
			'id'       => 'ssep_help',
			'type'     => 'info',
			'title'    => '',
			'subtitle' => '',
			'desc'     => __( 'The featured content section displays your "sticky" posts in the jumbotron.
				On first activation you will have to manually trigger the compiler (by changing for example the Jumbotron Background Color setting).', 'shoestrap' )
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

		$ssep_active_modules = isset( $ss_settings['ssep_active_modules'] ) ? $ss_settings['ssep_active_modules'] : array();

		if ( isset( $ssep_active_modules['hidemeta'] ) && '1' == $ssep_active_modules['hidemeta'] ) {
			// Hide meta info per-post
			require_once( plugin_dir_path(__FILE__) . 'includes/hide-meta.php' );
		}

		if ( isset( $ssep_active_modules['hidetitle'] ) && '1' == $ssep_active_modules['hidetitle'] ) {
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

		if ( isset( $ssep_active_modules['featured'] ) && '1' == $ssep_active_modules['featured'] ) {
			// Add Featured Content files
			if ( ! class_exists( 'Featured_Content' ) && 'plugins.php' !== $GLOBALS['pagenow'] ) {
				require_once( plugin_dir_path(__FILE__) . 'includes/class-Featured_Content.php' );
			}

			require_once( plugin_dir_path(__FILE__) . 'includes/featured.php' );
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
