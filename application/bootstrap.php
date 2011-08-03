<?php defined('SYSPATH') or die('No direct script access.');

// -- Environment setup --------------------------------------------------------

// Load the core Kohana class
require SYSPATH.'classes/kohana/core'.EXT;

if (is_file(APPPATH.'classes/kohana'.EXT))
{
	// Application extends the core
	require APPPATH.'classes/kohana'.EXT;
}
else
{
	// Load empty core extension
	require SYSPATH.'classes/kohana'.EXT;
}

/**
 * Set the default time zone.
 *
 * @see  http://kohanaframework.org/guide/using.configuration
 * @see  http://php.net/timezones
 */
date_default_timezone_set('America/Los_Angeles');

/**
 * Set the default locale.
 *
 * @see  http://kohanaframework.org/guide/using.configuration
 * @see  http://php.net/setlocale
 */
setlocale(LC_ALL, 'en_US.utf-8');

/**
 * Enable the Kohana auto-loader.
 *
 * @see  http://kohanaframework.org/guide/using.autoloading
 * @see  http://php.net/spl_autoload_register
 */
spl_autoload_register(array('Kohana', 'auto_load'));

/**
 * Enable the Kohana auto-loader for unserialization.
 *
 * @see  http://php.net/spl_autoload_call
 * @see  http://php.net/manual/var.configuration.php#unserialize-callback-func
 */
ini_set('unserialize_callback_func', 'spl_autoload_call');

// -- Configuration and initialization -----------------------------------------

/**
 * Set the default language
 */
I18n::lang('en-us');

/**
 * Set Kohana::$environment if a 'KOHANA_ENV' environment variable has been supplied.
 *
 * Note: If you supply an invalid environment name, a PHP warning will be thrown
 * saying "Couldn't find constant Kohana::<INVALID_ENV_NAME>"
 */
if (isset($_SERVER['KOHANA_ENV']))
{
	Kohana::$environment = constant('Kohana::'.strtoupper($_SERVER['KOHANA_ENV']));
}

/**
 * Initialize Kohana, setting the default options.
 *
 * The following options are available:
 *
 * - string   base_url    path, and optionally domain, of your application   NULL
 * - string   index_file  name of your index file, usually "index.php"       index.php
 * - string   charset     internal character set used for input and output   utf-8
 * - string   cache_dir   set the internal cache directory                   APPPATH/cache
 * - boolean  errors      enable or disable error handling                   TRUE
 * - boolean  profile     enable or disable internal profiling               TRUE
 * - boolean  caching     enable or disable internal caching                 FALSE
 */

Kohana::init(array(
	'base_url'   => '/kohana_demo/',
	'index_file' => FALSE,
	'errors' => TRUE,  // turn off kohana errors to get more detailed info on php errors.
));

/**
 * Attach the file write to logging. Multiple writers are supported.
 */
Kohana::$log->attach(new Log_File(APPPATH.'logs'));

/**
 * Attach a file reader to config. Multiple readers are supported.
 */
Kohana::$config->attach(new Config_File);

/**
 * Enable modules. Modules are referenced by a relative or absolute path.
 */
Kohana::modules(array(
	'auth'          => MODPATH.'auth',       // Basic authentication
	// 'cache'      => MODPATH.'cache',      // Caching with multiple backends
	// 'codebench'  => MODPATH.'codebench',  // Benchmarking tool
	'database'      => MODPATH.'database',   // Database access
	// 'image'      => MODPATH.'image',      // Image manipulation
	'orm'           => MODPATH.'orm',        // Object Relationship Mapping
	//'automodeler'   => MODPATH.'automodeler',        // Object Relationship Mapping
	//'unittest'      => MODPATH.'unittest',   // Unit testing
	//'userguide'     => MODPATH.'userguide',  // User guide and API documentation
	'kostache'      => MODPATH.'kostache',      // Kostache logic-less view
	'email'         => MODPATH.'email',      // Swiftmailer email
	'pagination'      => MODPATH.'pagination',  //Pagination
	'pastiche'      => MODPATH.'kostache-pagination-helper',  //Kostache pagination helper
));

/**
 * Set the routes. Each route must have a name and may specify a URI callback string and/or a regex array.
 * Defaults may be optionally be set.
 */


// Only match if the controller is 'error' and an integer action is specified.  An optional unconstrained message may follow.
Route::set('error', '<controller>/<action>(/<message>)', array('controller' => 'error', 'action' => '[0-9]++', 'message' => '.+'));

// Only match if a controller is specified.  If an id is optionally specified, it must be an integer
Route::set('default', '(<controller>(/<action>(/<id>)))', array('id' => '[0-9]++'))
	->defaults(array(
	'controller' => 'album',
	'action' => 'index',
	));

