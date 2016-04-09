<?php

/**
 * Handle the write of the CSS cache file
 *
 * Class SCSS_Cache_File
 */
class SCSS_Cache_File {

	/**
	 * @var null|string
	 */
	private $cache_folder = null;

	/**
	 * @var null|\WP_Filesystem_Direct
	 */
	private $file_system = null;

	/**
	 * Current cache file blog_id
	 *
	 * @var int
	 */
	private $blog_id;

	public function __construct( $blog_id = 1 ) {
		$this->blog_id = (int)$blog_id;
		$this->get_cache_folder();
		$this->get_filesystem();
		$this->init_cache_folder();
	}

	/**
	 * Create the cache folder
	 *
	 * @return bool
	 */
	private function init_cache_folder() {
		if( $this->file_system->is_dir( $this->cache_folder ) ) {
			return true;
		}

		return mkdir( $this->cache_folder, defined( 'FS_CHMOD_FILE' ) ? FS_CHMOD_FILE : 0755, true );
	}

	/**
	 * @return mixed
	 */
	private function get_cache_folder() {
		if( ! is_null( $this->cache_folder ) ) {
			return $this->cache_folder;
		}

		$folder = apply_filters( 'SCSS_Cache/folder_name','/cache/scss/' );
		$this->cache_folder = trailingslashit( WP_CONTENT_DIR.$folder, $this->blog_id, $this );
	}

	/**
	 * Get the file cache name
	 *
	 * @return mixed
	 */
	private function get_file_cache_name() {
		return sanitize_file_name( sprintf( '%d-scss.css', $this->blog_id ) );
	}

	/**
	 * Get the cache path
	 * concatenation of the foler and the file name
	 *
	 * @return string
	 */
	private function get_file_cache_path() {
		return $this->cache_folder.$this->get_file_cache_name( );
	}

	/**
	 * Get the relative path for the cache folder
	 *
	 * @return mixed
	 */
	public function get_file_cache_relative_path() {
		return str_replace( ABSPATH, '', $this->cache_folder.$this->get_file_cache_name( ) );
	}

	/**
	 * Get the cache file url
	 *
	 * @return mixed
	 */
	public function get_cache_url() {
		return home_url( $this->get_file_cache_relative_path() );
	}

	/**
	 * Check if the file exists
	 *
	 * @return bool
	 */
	public function is_cache_file() {
		return $this->file_system->is_file( $this->get_file_cache_path() );
	}

	/**
	 * Get the file system of WP
	 *
	 * @return \WP_Filesystem_Direct
	 * @author Nicolas Juen
	 */
	private function get_filesystem() {
		if( ! is_null( $this->file_system ) ) {
			return $this->file_system;
		}

		require_once( ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php' );
		require_once( ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php' );
		$this->file_system = new \WP_Filesystem_Direct( new \StdClass() );
		return $this->file_system;
	}

	/**
	 * Cache getter
	 *
	 * @return mixed
	 */
	public function get( ) {
		return ! $this->is_cache_file() ? $this->file_system->get_contents( $this->get_file_cache_path() ) : false;
	}

	/**
	 * Set the content into the cache file
	 *
	 * @param $data
	 *
	 * @return mixed
	 */
	public function set( $data ) {
		return $this->file_system->put_contents( $this->get_file_cache_path( ), $data, 0755 );
	}

	/**
	 * Flush the cache
	 *
	 * @param $key
	 *
	 * @return mixed
	 */
	public function flush( ) {
		if( $this->is_cache_file( ) ) {
			return $this->file_system->delete( $this->get_file_cache_path( ), false, 'f' );
		}

		return true;
	}

}