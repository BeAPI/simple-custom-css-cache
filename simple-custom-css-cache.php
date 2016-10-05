<?php
/*
 Plugin Name: Simple Custom CSS Cache
 Version: 1.0.1
 Plugin URI: http://www.beapi.fr
 Description: Cache the Simple Custom CSS option to a real file
 Author: BE API Technical team
 Author URI: http://www.beapi.fr
 Domain Path: languages
 Text Domain: simple-custom-css-cache
 
 ----
 
 Copyright 2016 BE API Technical team (human@beapi.fr)
 
 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.
 
 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.
 
 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

// Prevent direct file access
if( ! defined( 'ABSPATH' ) ) {
	die();
}

require_once dirname( __FILE__ ) . '/inc/cache.php';

class SCSS_Cache{
	/**
	 * The SCSS_Cache_File object
	 *
	 * @var SCSS_Cache_File
	 */
	private $cache_file;

	public function __construct( $blog_id ) {
		/**
		 * Setup blog and cache file
		 */
		$this->cache_file = new SCSS_Cache_File( absint( $blog_id ) );

		/**
		 * On option update, refresh the cache file
		 **/
		add_action( 'update_option_sccss_settings', array( $this, 'refresh_cache_file' ) );

		/**
		 * Remove the data from the SCSS
		 * Enqueue our own SCSS file
		 */
		add_action( 'wp_enqueue_scripts', array( $this, 'register_style' ), 1 );
	}

	/**
	 * Generate the file on activation
	 */
	public function activation() {
		$this->refresh_cache_file();
	}

	/**
	 * Delete file upon deactivation
	 */
	public function deactivation() {
		$this->cache_file->flush();
	}

	/**
	 * SCSS handle
	 */
	public function register_style() {
		/**
		 * Do not enqueue not cached files
		 */
		if ( ! $this->cache_file->is_cache_file() ) {
			return;
		}

		wp_register_style( 'sccss_style', $this->cache_file->get_cache_url() );
		wp_enqueue_style( 'sccss_style' );

		remove_action( 'wp_enqueue_scripts', 'sccss_register_style', 99 );
	}

	/**
	 * Refresh the cache file based on the options from the backoffice
	 *
	 * @return bool
	 */
	public function refresh_cache_file() {
		$options     = get_option( 'sccss_settings' );
		$raw_content = isset( $options['sccss-content'] ) ? $options['sccss-content'] : '';
		$content     = wp_kses( $raw_content, array( '\'', '\"' ) );
		$content     = str_replace( '&gt;', '>', $content );

		// Replace the file content
		$this->cache_file->flush();
		return $this->cache_file->set( $content );
	}
}

/**
 * Generate the class
 **/
$scss_cache = new SCSS_Cache( get_current_blog_id() );

/**
 * Generate files upon activation
 * Remove files upon deactivation
 */
register_activation_hook( __FILE__, array( $scss_cache, 'activation' ) );
register_deactivation_hook( __FILE__, array( $scss_cache, 'deactivation' ) );
