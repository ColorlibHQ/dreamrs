<?php

/**
 * Epsilon Dashboard  Autoloader
 *
 * @package Dreamrs
 * @since   1.1.0
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Epsilon_Dashboard_Autoloader
 */
class Epsilon_Dashboard_Autoloader {
	/**
	 * Epsilon_dashboard_Autoloader constructor.
	 */
	public function __construct() {

		spl_autoload_register( array( $this, 'load' ) );
	}

	/**
	 * This function loads the necessary files
	 *
	 * @param string $class CLASS NAME.
	 */
	public function load( $class = '' ) {

		/**
		 * All classes are prefixed with Dreamrs_
		 */
		$parts = explode( '_', $class );
		$bind  = implode( '-', $parts );

		/**
		 * We provide working directories
		 */
		$directories = array(
			DREAMRS_DIR_PATH_LIB ,
			DREAMRS_DIR_PATH_LIB . 'epsilon-framework/',
			DREAMRS_DIR_PATH_LIB . 'epsilon-theme-dashboard/',
			DREAMRS_DIR_PATH_LIB . 'epsilon-theme-dashboard/inc/',
			DREAMRS_DIR_PATH_LIB . 'epsilon-theme-dashboard/inc/helpers/',
			DREAMRS_DIR_PATH_LIB . 'epsilon-theme-dashboard/inc/misc/',
			DREAMRS_DIR_PATH_LIB . 'epsilon-theme-dashboard/inc/misc/demo-generators/',
			DREAMRS_DIR_PATH_LIB . 'epsilon-theme-dashboard/inc/misc/epsilon-tracking/',
			DREAMRS_DIR_PATH_LIB . 'epsilon-theme-dashboard/inc/misc/epsilon-tracking/trackers/',
		);

		/**
		 * Loop through them, if we find the class .. we load it !
		 */
		foreach ( $directories as $directory ) {
			if ( file_exists( $directory . 'class-' . strtolower( $bind ) . '.php' ) ) {
				require_once $directory . 'class-' . strtolower( $bind ) . '.php';

				return;
			}
		}


	}
}

new Epsilon_Dashboard_Autoloader();
