The DOS Spirit 
==============
Spel fr� dei gamle dagane, Games from ye olde times - Since 1832�
-----------------------------------------------------------------

The DOS Spirit courtesy of The DOS Spirit Foundation

The DOS Spirit is a high availability, high performance site focused on categorizing, indexing and organizing games and applications on a wide variety of platforms from the early 80s, 90s and up to the 00s. 


The Project
----------
Started initially back in 2004 as a private home page under the name "bakkelun dot com", but separated as its own project early 2005 called "The DOS Spirit". 


Installation
-----------
This install requires that you've already setup PHP and MySQL on your local development machine. You can also use other xSQL as long as you change the config to use this driver and update the schema as well. 
- Grab a copy of CakePHP 2.X.X, http://cakephp.org/. Unpack to a folder of your choice.
- Configure the path, YOUR-CAKEPHP-LIBRARY-PATH, in webroot/index.php to point to where you unpacked the CakePHP folder. Alternatively change the CAKE_CORE_INCLUDE_PATH in root/index.php to your CakePHP path. 
- Update app/Config/database.php to include your db connection credentials.
- Import the schema, found in build/tds-schema.sql. (This is written in MySQL). Remember to create the schema first, the schema expects "tds", but  you are free to edit this in your local installation.
- Move the Config folder from build to app so it becomes app/Config.
- In the app/webroot folder, create these folders: (Indentations mean subfolders in that folder)
   - gamefiles
   - images
      - games
          - cover
          - focus
          - ingress
    - tmp
	  - cache
	    - images

- Set the permissions to either 755 or 605 on your local dev environment.
- Update security salt and cipherseed in app/Config/Core.php.
- That should be it, but things will fail as there is no content present. This is normal. You can use the application to create test data for yourself.

Language and translation
------------------------
To contribute to translating The DOS Spirit into your locale, please see The DOS Spirit Crowdin Project page for this: http://crowdin.net/project/dosspirit
Currently The DOS Spirit supports 9 languages including English, Spanish, Italian and Norwegian.

Build process is abit convoluted. It requires you to run the cakephp i18n shell console to correctly generate a new "default.pot" file, located in "app/Locale/default.pot". Have a look here on how that works: http://book.cakephp.org/2.0/en/console-and-shells/i18n-shell.html

New locale updates to the application must checked in and done through pull requests. Then uploaded to Crowdin via the project's admin, bakkelun. Newly generated locale files (.po, located in Locale/<language>/LC_MESSAGES/default.po) will then be checked into master.