Kohana Demo Application
=======================
A simple demo application for those who learn best from simple, working sample code and want to see a sampling of Kohana features in action.

For a limited time, you can try it out at this [demo site](http://optocare.angryhosting.com/)

- admin login -> username: administrator, password: admin12345
- regular login -> username: joseph, password: ghghghghgh

Feel free to add your own users and albums and edit and delete them as you choose, but out of courtesy to others, please don't remove either of the preset users or change their passwords.

If either of those passwords doesn't work, it probably means that someone was being impulsive... ;-)  Just be patient and try again later.  I'll get a notification if either of these users change and will try to keep it working from 9-5 PST.

Features
--------
- Reverse Routing
- Templating with Kostache
- Basic Auth, including the ability to manage users. A user has the 'login' role by default and can optionally be assigned the 'admin' role
- Form validation, displaying default error messages
- Custom application error messages
- ORM with MySQL database
- Email notification using Swiftmailer (this requires an email server, but if you don't have one, the application won't break)

Installation
------------
###Installing Files Using Git (Recommended)
- create a directory
- open a git bash there
- `$ git clone git://github.com/ddrake/kohana_demo.git .`
- `$ git fetch --all`
- `$ git submodule update --init --recursive`

For more information on Git, see the [Kohana Git Tutorial](http://kohanaframework.org/3.0/guide/kohana/tutorials/git)

###Installing Files Without Git
- Download [Kohana v3.1.2](http://kohanaframework.org/download)
- Install [Kohana v3.1.2](http://kohanaframework.org/3.1/guide/kohana/install)
- Install the [KOstache v2.0.4 module](http://github.com/zombor/KOstache) into modules/kostache/
- Delete controller/welcome.php if so desired
- Copy the files in the application directory of this project into 'application'.
- Copy the files in the assets directory of this project into 'assets'.
- Copy the [Kostache files from here](http://github.com/zombor/KOstache) to modules/kostache
- Copy the [Mustche files from here](http://github.com/bobthecow/mustache.php) to modules/kostache/vendor/mustache

###Configuration
- Make sure the application/cache and application/logs directories have write access.
- Rename the example.htaccess included with this application to .htaccess and edit the RewriteBase to match your installation directory.  For more information see [here](http://kohanaframework.org/3.0/guide/kohana/tutorials/clean-urls)
- Leave the line `SetEnv KOHANA_ENV development` as is for now.  Later you can change it to production to use the custom error handler.
- Rename the application/example.bootstrap.php included with this application to bootstrap.php and set base_url to match your installation directory.
- Check that the following modules are enabled in bootstrap.php: auth, orm, database, kostache, email
- Edit application/config/auth.php setting the hash_key to a random string of your choice.
- Create a mysql database
- Edit application/config/database.php to match the database you created
- Execute the schema in initial_schema.sql.
- If you're installing to a live server and want to test email notification about a primary user being deleted or having their password changed, edit aplication/config/email.php to set the to and from email addresses.

You should be good to go.  The default admin login is:
- username: administrator
- password: admin12345

but you can add a new administrator and delete that one if you like.

If you're working in a Windows (WAMP Server) environment, you may also find these useful:

- [Tutorial for Git with Windows](http://dowdrake.com/showthread.php?400-A-nice-tutorial-for-Git-with-Windows) and
- [Setting up to Help Document Kohana - Git/Win XP](http://dowdrake.com/showthread.php?401-Setting-up-to-help-document-Kohana-Git-Win-XP)

See Also
--------
For a much more elaborate example of the capabilities of the Auth module and loads of other useful information, I would recommend

- [The Unofficial Kohana 3 Wiki](http://kerkness.ca/wiki/doku.php)

Credits
-------
I've borrowed from several sources, but one in particular should probably be mentioned:

[Kohana: The Swift PHP Framework](http://net.tutsplus.com/tutorials/php/kohana-the-swift-php-framework/)
This was a Kohana 2 tutorial and its code bears almost no resemblance to that of this project, but I borrowed its simple schema and button images.
