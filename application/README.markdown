Kohana Demo Application
=======================
A basic tutorial for those who learn best from simple, working sample code and want to see a sampling of Kohana features in action

Features
--------
- Reverse Routing
- Templating with Kostache
- Basic Auth, including the ability to manage users with login role and optionally admin role
- Form validation, displaying default error messages
- Custom application error messages
- ORM with MySQL database

Installation
------------
Installation Instructions (non-Git - see footnote *) 
Download Kohana v3.1.2 http://kohanaframework.org/download
Install Kohana v3.1.2  http://kohanaframework.org/3.1/guide/kohana/install
Install the kostache v2.0.4 module: http://github.com/zombor/KOstache into modules/kostache/

Follow the instructions here for clean urls:  http://kohanaframework.org/3.0/guide/kohana/tutorials/clean-urls

Additionally, in your .htaccess, add the following lines:
# Set Kohana environment to 'development' (to use custom error handler, set this to 'production').
SetEnv KOHANA_ENV development 

Delete controller/welcome.php if so desired

Copy the files in the application directory of this project into 'application'. 
Copy the files in the assets directory of this project into 'assets'.

Copy the Kostache files from here: https://github.com/zombor/KOstache to modules/kostache
Copy the Mustche files from here: https://github.com/bobthecow/mustache.php to modules/kostache/vendor/mustache

In your application/bootstrap.php, 
- Enable the following modules: auth, orm, database, kostache -- it should look something like this:

    Kohana::modules(array(
    	'auth'       => MODPATH.'auth',       // Basic authentication
    	// 'cache'      => MODPATH.'cache',      // Caching with multiple backends
    	// 'codebench'  => MODPATH.'codebench',  // Benchmarking tool
    	'database'   => MODPATH.'database',   // Database access
    	// 'image'      => MODPATH.'image',      // Image manipulation
    	'orm'        => MODPATH.'orm',        // Object Relationship Mapping
    	// 'unittest'   => MODPATH.'unittest',   // Unit testing
    	// 'userguide'  => MODPATH.'userguide',  // User guide and API documentation
    	 'kostache'  => MODPATH.'kostache',  // User guide and API documentation
    	));


- Add the following routes:
    Route::set('normal', '(<controller>(/<action>(/<id>)))', array('id' => '[0-9]++'))
    	->defaults(array(
    		'controller' => 'album',
    		'action'     => 'index',
    		'id'         => NULL,
    	));

    Route::set('error', 'error/<action>(/<message>)', array('action' => '[0-9]++', 'message' => '.+'))
    	->defaults(array(
    		'controller' => 'error'
    	));


- Edit the default route to the following:
    Route::set('default', '(<controller>(/<action>(/<id>)))')
    	->defaults(array(
    		'controller' => 'album',
    		'action'     => 'index',
    	));


Edit application/config/auth.php setting the hash_key to a random string of your choice.

Create a mysql database and modify application/config/database.php as required
Execute the schema in initial_schema.sql.

You should be good to go.  The default admin login is:
username: administrator
password: admin12345


* It's simpler and better to use Git submodules, to do so, refer to: 
[Kohana Git Tutorial](http://kohanaframework.org/3.0/guide/kohana/tutorials/git)

If you're working in a Windows (WAMP Server) environment, you may also find these useful:
[Tutorial for Git with Windows](http://dowdrake.com/showthread.php?400-A-nice-tutorial-for-Git-with-Windows)
[Setting up to Help Document Kohana - Git/Win XP](http://dowdrake.com/showthread.php?401-Setting-up-to-help-document-Kohana-Git-Win-XP)

See Also
--------
For a much more elaborate example of the capabilities of the Auth module and loads of other useful information, I would recommend [The Unofficial Kohana 3 Wiki](http://kerkness.ca/wiki/doku.php)

Credits
-------
I've borrowed from several sources, but one in particular should probably be mentioned:
[Kohana: The Swift PHP Framework](http://net.tutsplus.com/tutorials/php/kohana-the-swift-php-framework/)
This was a Kohana 2 tutorial and its code bears almost no resemblance to that of this project, but I borrowed its simple schema and button images.
