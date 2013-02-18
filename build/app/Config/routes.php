<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.config
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
Router::connect('/:language/news/:newsslug/:newsid',
array('controller' => 'news', 'action' => 'view'),
array('pass' => array('newsslug', 'newsid')));

Router::connect('/:language/news',
array('controller' => 'news', 'action' => 'index'));

Router::connect('/:language/game/random',
    array('controller' => 'game', 'action' => 'random')
);

Router::connect('/game/random',
    array('controller' => 'game', 'action' => 'random')
);

Router::connect('/:language/game/:gameslug/:id',
array('controller' => 'game', 'action' => 'view'),
array('pass' => array('gameslug', 'id')));

Router::connect('/game/:gameslug/:id',
array('controller' => 'game', 'action' => 'view'),
array('pass' => array('gameslug', 'id')));

Router::connect('/:language/games/*', array('controller' => 'game', 'action' => 'all'));
Router::connect('/games/*', array('controller' => 'game', 'action' => 'all'));

// Define the standard route for search.
Router::connect('/:language/search', array('controller' => 'search', 'action' => 'index'));
Router::connect('/:language/search/*', array('controller' => 'search', 'action' => 'search'));

Router::connect('/search', array('controller' => 'search', 'action' => 'index'));
Router::connect('/search/*', array('controller' => 'search', 'action' => 'search'));

Router::connect('/profile/:userslug/:userid',
array('controller' => 'user', 'action' => 'index'),
array('pass' => array('userslug', 'userid')));

Router::connect('/:language/profile/:userslug/:userid',
array('controller' => 'user', 'action' => 'index'),
array('pass' => array('userslug', 'userid'))
);

Router::connect('/:language/contact/*', array('controller' => 'contact', 'action' => 'index'));
Router::connect('/contact/*', array('controller' => 'contact', 'action' => 'index'));

Router::connect('/game/preview/:gameslug/:id',
    array('controller' => 'game', 'action' => 'preview'),
    array('pass' => array('gameslug', 'id'))
);

Router::connect('/login', array('controller' => 'admin', 'action' => 'index'));

Router::connect('/admin/editor/preview',
    array('controller' => 'admin', 'action' => 'editorpreview'));
// Note: Set critical routes first, then "catch all" routes after.

//Router::connect('/search', array('controller' => 'search', 'action' => 'search'));
//Router::connect('/:language/search', array('controller' => 'search', 'action' => 'index'));
//Router::connect('/news', array('controller' => 'news', 'action' => 'index'));

Router::redirect('/omtale/*', '/games', array('status' => 302));
Router::redirect('/forum/*', 'http://www.multigamer.no/forum', array('status' => 302));

Router::connect('/season/:season/:year',
        array('controller' => 'season', 'action' => 'index'),
        array('pass' => array('season', 'year'))
);

// Add support for urls like /search/category/puzzle/page:3
Router::connect('/search/ajax/:term',
    array('controller' => 'search', 'action' => 'ajax'),
    array('pass' => array('term'))
);
Router::connect('/radio', array('controller' => 'radio', 'action' => 'index'));
Router::connect('/articles', array('controller' => 'articles', 'action' => 'index'));
Router::connect('/:language/articles', array('controller' => 'articles', 'action' => 'index'));

// Provide general fallback.

// Add download support
Router::connect('/download/*', array('controller' => 'download', 'action' => 'index'));

// Define the default route when entering domain.
Router::connect('/', array('controller' => 'main', 'action' => 'index'));
Router::connect('/:language', array('controller' => 'main', 'action' => 'index'));



/**
 * Load all plugin routes.  See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
CakePlugin::routes();

/**
 * Load the CakePHP default routes. Remove this if you do not want to use
 * the built-in default routes.
 */
require CAKE . 'Config' . DS . 'routes.php';