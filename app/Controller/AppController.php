<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
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
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
    public $siteSettings;
    public $user;
    public $validSeasons = array('winter', 'spring', 'summer', 'christmas', 'easter');
    public $selectedLanguage;
    public $components = array('CommonFunctions', 'Session', 'Cookie',
    'Auth' => array(
        'loginAction' => array(
            'controller' => 'admin',
            'action' => 'login',
        ),
        'loginRedirect' => array(
            'controller' => 'admin',
            'action' => 'dashboard'
        ),
        'logoutRedirect' => array(
            'controller' => '/',
            'action' => 'index'
        ),
        'authError' => 'Please login',
        'authenticate' => array(
            'Form' => array(
                'fields' => array('username' => 'email', 'password' => 'password'),
        		'scope' => array('User.activated' => 1)
            )
        )
    )
    );

    // Define helpers we need throughout the applicaiton.
    public $helpers = array('Form', 'Html', 'CommonViewFunctions', 'Session', 'Cache', 'Paginator');

    public function beforeFilter() {
        // Provide the site settings for the entire site, set it as a public variable parenting all controllers.
        $this->loadModel('SiteSetting');
        $this->siteSettings = $this->SiteSetting->getSettings();

        $this->_setLanguage();
        $this->Auth->allow('*');

        if (date('m', time()) == 12) {
            $this->theme = 'Christmas';
        }

        // Set the user object accessible globally.
        if ($this->Auth->user('id')) {
            $this->set('user', $this->Auth->user());

            $this->loadModel('UserInbox');
            $inboxCount = $this->UserInbox->find('count', array(
                    'conditions' => array('UserInbox.user_id' => $this->Auth->user('id'))
            ));

            $this->user = $this->Auth->user();
            $this->set('userInboxCount', $inboxCount);
        }

        $this->set('validSeasons', $this->validSeasons);
    }

    /**
     * Handle language change.
     */
    private function _setLanguage() {

        if ($this->Cookie->read('lang') && !$this->Session->check('Config.language')) {
            $this->Session->write('Config.language', $this->Cookie->read('lang'));
        } else if (isset($this->request->params['language']) && ($this->request->params['language'] !=  $this->Session->read('Config.language'))) {
            // Validate language.
            $validLanguages = array('eng', 'no-nb', 'no-nn', 'spa', 'deu', 'ita', 'eng-pt');

            if (in_array($this->request->params['language'], $validLanguages)) {
                $this->Session->write('Config.language', $this->request->params['language']);
            } else {
                // Default back to english if invalid parameter is found.
                $this->request->params['language'] = 'eng';
            }
            $this->Cookie->write('lang', $this->request->params['language'], false, '20 days');
        } else {
            $this->Session->write('Config.language', 'eng');
        }

        $this->selectedLanguage = $this->Session->read('Config.language');
        $this->set('selectedLanguage', $this->selectedLanguage);
    }
}
