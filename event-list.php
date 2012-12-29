<?php
/*
Plugin Name: Event List
Plugin URI: http://wordpress.org/extend/plugins/event-list/
Description: Manage your events and show them in a list view on your site.
Version: 0.2.2
Author: Michael Burtscher
Author URI: http://wordpress.org/extend/plugins/event-list/
License: GPLv2

A plugin for the blogging MySQL/PHP-based WordPress.
Copyright 2012 Michael Burtscher

This program is free software; you can redistribute it and/or
modify it under the terms of the GNUs General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You can view a copy of the HTML version of the GNU General Public
License at http://www.gnu.org/copyleft/gpl.html
*/

// general definitions
define( 'EL_URL', plugin_dir_url( __FILE__ ) );
define( 'EL_PATH', plugin_dir_path( __FILE__ ) );


// ADD HOOKS, ACTIONS AND SHORTCODES:

// FOR ADMIN AND FRONTPAGE:
// TODO: Check the following hooks
//register_activation_hook( 'php/db.php', array( 'el_db', 'upgrade_check' ) );
//register_activation_hook( 'php/options.php', array( 'el_options', 'upgrade' ) );
add_action( 'plugins_loaded', 'on_el_plugin_loaded' );
// TODO: Add widget
//add_action( 'widgets_init', 'on_el_widgets' );

// ADMIN PAGE:
if ( is_admin() ) {
	// Include required php-files and initialize required objects
	require_once( 'php/admin.php' );
	$admin = new el_admin();
	// Register actions
	add_action( 'admin_menu', array( &$admin, 'register_pages' ) );
//	add_action( 'admin_init', 'on_el_register_settings' ); // register settings
}

// FRONT PAGE:
else {
	add_shortcode( 'event-list', 'on_el_sc_event_list' ); // add shortcode [event-list]
	// Stylesheet for display
	add_action('wp_print_styles', 'on_el_styles');
}

function on_el_plugin_loaded() {
	require_once( 'php/db.php' );
	el_db::update_check();
}

/*
function on_el_register_settings() {
	require_once( 'php/options.php' );
	el_options::register();
}
*/
function on_el_sc_event_list( $atts ) {
	require_once( 'php/sc_event-list.php' );
	$shortcode = sc_event_list::get_instance();
	return $shortcode->show_html( $atts );
}

function on_el_styles() {
	wp_register_style('event-list_css', EL_URL.'css/event-list.css');
	wp_enqueue_style( 'event-list_css');
}

/*
function on_el_widgets() {
	require_once( 'php/event-list_widget.php' );
	return register_widget( 'event_list_widget' );
}
*/
?>
