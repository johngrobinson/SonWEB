<?php
	ini_set( 'session.gc_maxlifetime', 2678400 );
	error_reporting( E_ALL );
	ini_set( 'display_errors', '1' );
	session_start();
	
	if ( !function_exists( "curl_init" ) ) {
		echo "ERROR: PHP Curl is missing.";
		echo "Please install PHP Curl";
		echo "sudo apt-get install php7.0-curl";
		echo "and restart webserver";
		die();
	}
	
	if ( !class_exists( "ZipArchive" ) ) {
		echo "ERROR: PHP Zip is missing.";
		echo "Please install PHP Zip";
		echo "sudo apt-get install php7.0-zip";
		echo "and restart webserver";
		die();
	}
	define( "_VERSION_", "1.0.1a" );
	
	define( "_APPROOT_", "./" );
	define( "_RESOURCESDIR_", _APPROOT_."resources/" );
	define( "_INCLUDESDIR_", _APPROOT_."includes/" );
	define( "_LIBSDIR_", _APPROOT_."libs/" );
	define( "_PAGESDIR_", _APPROOT_."pages/" );
	define( "_DATADIR_", _APPROOT_."data/" );
	define( "_LANGDIR_", _APPROOT_."lang/" );
	define( "_TMPDIR_", _APPROOT_."tmp/" );
	
	/**
	 * @property Sonoff Sonoff
	 */
	require_once _INCLUDESDIR_."Config.php";
	require_once _INCLUDESDIR_."Sonoff.php";
	require_once _LIBSDIR_.'phpi18n/i18n.class.php';
	require_once _INCLUDESDIR_."Config.php";
	
	$i18n = new i18n();
	
	$lang = isset( $_GET[ "lang" ] ) ? $_GET[ "lang" ] : NULL;
	if ( isset( $lang ) ) {
		$_SESSION[ 'lang' ] = $lang;
	}
	
	
	$i18n->setCachePath( _TMPDIR_.'cache/i18n/' );
	$i18n->setFilePath( _LANGDIR_.'lang_{LANGUAGE}.ini' ); // language file path
	$i18n->setFallbackLang( 'en' );
	$i18n->setPrefix( '__L' );
	$i18n->setSectionSeperator( '_' );
	$i18n->setMergeFallback( TRUE ); // make keys available from the fallback language
	$i18n->init();
	
	$lang = $i18n->getAppliedLang();
	
	
	$Config = new Config();
	$Sonoff = new Sonoff();
	
	
	function __( $string, $category = NULL, $args = NULL ) {
		$cat = "";
		if ( isset( $category ) && !empty( $category ) ) {
			$cat = $category."_";
		}
		$txt        = $cat.$string;
		$translated = @__L::$txt( $args );
		
		if ( $translated == "" ) {
			$myfile = fopen( _LANGDIR_."lang_new.ini", "a" ) or die( "Unable to open file!" );
			$translated = $txt;
			fwrite( $myfile, $txt );
			fclose( $myfile );
			$files = glob( _TMPDIR_.'cache/i18n/*' ); // get all file names
			foreach ( $files as $file ) { // iterate files
				if ( is_file( $file ) ) {
					unlink( $file );
				}
			}
			
		}
		
		return $translated;
	}
	
	if ( isset( $_GET ) ) {
		if ( isset( $_GET[ "doAjax" ] ) ) {
			$data = $Sonoff->doAjax( $_GET[ "doAjax" ] );
			echo json_encode( $data );
			die();
		}
	}
	