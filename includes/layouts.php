<?php

/**
 * Add themetabox field
 */
function ssep_layout_metabox( $fields ) {

	$fields[] = array(
		'id'      => '_ss_assign_layout',
		'name'    => __( 'Assign Layout' ),
		'type'    => 'select',
		'options' => array(
			'd'   => __( 'Default', 'shoestrap' ),
			'f'   => __( 'Full-Width', 'shoestrap' ),
			'r'   => __( 'Right Sidebar', 'shoestrap' ),
			'l'   => __( 'Left Sidebar', 'shoestrap' ),
			'll'  => __( '2 Left Sidebars', 'shoestrap' ),
			'rr'  => __( '2 Right Sidebars', 'shoestrap' ),
			'lr'  => __( '1 Left & 1 Right Sidebars', 'shoestrap' ),
		),
		'desc'    => __( 'Assign a custom Layout to this post. Please note that this functionality depends on the active framework. If the default framewor (bootstrap) has been overriden, then this might not work as expected.', 'shoestrap' )
	);

	return $fields;
}
add_filter( 'ssep_metabox_fields', 'ssep_layout_metabox' );

/**
 * Force the selected layout
 */
function ssep_force_layout() {
	global $post, $ss_layout;

	$layout = (is_object($post)) ? apply_filters( 'shoestrap_forced_layout', get_post_meta( $post->ID, '_ss_assign_layout', true ) ) : 'd';

	// No need to continue if we've selected the default option.
	if ( 'd' == $layout ) {
		return;
	}

	if ( 'f' == $layout ) { // Full-width

		$ss_layout->set_layout( 0 );
		add_filter( 'shoestrap_display_primary_sidebar', '__return_false', 999 );
		add_filter( 'shoestrap_display_secondary_sidebar', '__return_false', 999 );

	} elseif ( 'r'  == $layout ) { // Right Sidebar

		$ss_layout->set_layout( 1 );
		add_filter( 'shoestrap_display_primary_sidebar', '__return_true', 999 );
		add_filter( 'shoestrap_display_secondary_sidebar', '__return_false', 999 );

	} elseif ( 'l'  == $layout ) { // Left Sidebar

		$ss_layout->set_layout( 2 );
		add_filter( 'shoestrap_display_primary_sidebar', '__return_true', 999 );
		add_filter( 'shoestrap_display_secondary_sidebar', '__return_false', 999 );

	} elseif ( 'll'  == $layout ) { // 2 Left Sidebars

		$ss_layout->set_layout( 3 );
		add_filter( 'shoestrap_display_primary_sidebar', '__return_true', 999 );
		add_filter( 'shoestrap_display_secondary_sidebar', '__return_true', 999 );

	} elseif ( 'rr'  == $layout ) { // 2 Right Sidebars

		$ss_layout->set_layout( 4 );
		add_filter( 'shoestrap_display_primary_sidebar', '__return_true', 999 );
		add_filter( 'shoestrap_display_secondary_sidebar', '__return_true', 999 );

	} elseif ( 'lr'  == $layout ) { // 1 Left & 1 Right Sidebars

		$ss_layout->set_layout( 5 );
		add_filter( 'shoestrap_display_primary_sidebar', '__return_true', 999 );
		add_filter( 'shoestrap_display_secondary_sidebar', '__return_true', 999 );

	}
}
add_action( 'wp', 'ssep_force_layout' );

function ssep_return_f() { return 'f'; }

function ssep_return_l() { return 'l'; }

function ssep_return_r() { return 'r'; }

function ssep_return_ll() { return 'll'; }

function ssep_return_rr() { return 'rr'; }

function ssep_return_lr() { return 'lr'; }
