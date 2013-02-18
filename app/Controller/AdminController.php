<?php

class AdminController extends AppController {
    public $components = array('RequestHandler', 'Session', 'Paginator', 'MathCaptcha', 'CommonFunctions', 'RandomKey' , 'Email', 'Zip');

    private $_pageType;

    private $_actionType;

    //var $scaffold = 'achievements';

    public function beforeFilter() {
        // Any controller using beforeFilter, need to call the parent to get components needed.
        parent::beforeFilter();

        $this->Auth->deny();
        $this->Auth->allow(array('register', 'change', 'activate', 'reset'));
        $this->layout = 'admin';

        $this->Paginator->settings = array(
                    'fields' => array('Game.name', 'Game.year', 'Game.ingress', 'Game.focus', 'Game.slug', 'Game.id', 'User.id', 'User.username'),
                    'order' => array('Game.name' => 'ASC'),
                    'limit' => 12
        );
    }

    public function index() {
        if ($this->Auth->loggedIn()) {
            $this->redirect('/admin/dashboard');
        } else {
            $this->redirect('/admin/login');
        }
    }

    public function companies() {
        $this->loadModel('Company');
        $companies = $this->Company->find('all', array('order' => 'Company.name ASC'));
        $this->set('companies', $companies);
    }

    public function purgecache() {
        $clearedShort = cache::clear(false, 'short');
        $clearedLong = cache::clear(false, 'long');

        $files = glob(CACHE . 'images' . DS . '*'); // get all file names
        foreach($files as $file) { // iterate files
            if(is_file($file)) {
                unlink($file); // delete file
            }
        }

        if ($clearedShort && $clearedLong) {
            $this->Session->setFlash('Caches were cleared');
        } else {
            $this->Session->setFlash('Something bugs went down. Caches were NOT cleared');
        }

        $this->redirect('/admin/dashboard');
    }

    public function series() {
        $this->loadModel('Serie');

        $series = $this->Serie->find('all', array('order' => 'Serie.name ASC'));
        $this->set('series', $series);

        $this->render('series');
    }

    /**
     * Handles login, adds some more behaviour.
     */
    public function login() {
        // Redirect if the user already is logged in
        if ($this->Auth->loggedIn()) {
            $this->redirect('/admin/dashboard');
        } else {
            if ($this->request->is('post')) {
                if ($this->Auth->login()) {
                    // Update last logged in for user.
                    $this->loadModel('User');
                    $user['User']['id'] = $this->Auth->user('id');
                    $user['User']['last_logged_in'] = date('Y-m-d H:i:s', time());

                    $this->User->save($user, false, array('last_logged_in'));


                    $this->redirect('/admin/dashboard');

                } else {
                    $this->Session->setFlash(__('Invalid username or password'));
                }
            }
        }
    }

    /**
     *
     * Allows for ajax editor preview in admin.
     */
    public function editorpreview() {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            echo $this->CommonFunctions->bbCodeString(nl2br($this->data));
        }
    }

    public function dashboard() {
    }

    public function media() {
        $this->loadModel('MediaPool');

        $experienceReward = $this->siteSettings['SiteSetting']['reward_validate_media'];

        $userInboxMessages = array();
        $points = 0;

        if ($this->request->is('post')) {
            $mediaPoolArray = array();
            $mediaPoolBlock = array();
            foreach ($this->data['MediaPool']['selected'] as $selected) {
                $mediaEntry = $this->MediaPool->findById($selected);

                if ($mediaEntry['MediaPool']['active'] == 0) {
                    $mediaEntry['MediaPool']['active'] = 1;
                    $mediaPoolBlock[] = $mediaEntry;
                    $userInboxMessages['UserInbox']['user_id'] = $this->Auth->user('id');
                    $userInboxMessages['UserInbox']['activity'] = 'Approved media file ' . $mediaEntry['MediaPool']['name'];
                    $userInboxMessages['UserInbox']['link'] = $mediaEntry['MediaPool']['name'];
                    $userInboxMessages['UserInbox']['reward'] = $experienceReward;
                    $userInboxMessages['UserInbox']['type'] = 'accepted media';
                    $userInboxArray[] = $userInboxMessages;

                    $userInboxMessages['UserInbox']['user_id'] = $mediaEntry['MediaPool']['user_id'];
                    $userInboxMessages['UserInbox']['activity'] = 'Your media file ' . $mediaEntry['MediaPool']['name'] . ' was approved by ' . $this->Auth->user('username');
                    $userInboxMessages['UserInbox']['link'] = $mediaEntry['MediaPool']['name'];
                    $userInboxMessages['UserInbox']['reward'] = 0;
                    $userInboxMessages['UserInbox']['type'] = 'media accepted';
                    $userInboxArray[] = $userInboxMessages;

                    $points += $experienceReward;
                }
            }

            if (count($mediaPoolBlock) > 0) {
                if ($this->MediaPool->saveAll($mediaPoolBlock)) {
                    // Reward the user who administrated the media files.
                    $userObject = $this->_rewardUser($this->Auth->user('id'), $points);
                    // Save messages to the inbox
                    $this->loadModel('UserInbox');
                    $this->UserInbox->saveAll($userInboxArray);
                    $this->Session->setFlash(__('%s media files was/were approved and you got %s experience for approving them. Check your inbox for more details.', array(count($mediaPoolBlock), $points)));

                    $this->_refreshAuth($userObject);
                }
            }
        }

        $mediaThatNeedsApproval = $this->MediaPool->findAllByActive(0, array(), array('MediaPool.created' => 'desc'));
        $this->set('mediaList', $mediaThatNeedsApproval);
    }

    private function _refreshAuth($user) {
        $u = Set::merge($this->Auth->user(), $user['User']);

        $this->Auth->login($u);
    }

    public function inbox() {
        $this->loadModel('UserInbox');

        $inbox = $this->UserInbox->getInbox($this->Auth->user('id'));

        $this->Paginator->settings = array(
            'conditions' => array('UserInbox.user_id' => $this->Auth->user('id')),
            'fields' => array('UserInbox.*'),
            'order' => array('UserInbox.created' => 'DESC'),
            'limit' => 12,
            'maxLimit' => 10
        );

        $this->set('userInbox', $this->Paginator->paginate('UserInbox'));
    }

    public function newsletter() {
        $this->loadModel('User');

        // Find a list of all users whose email is not empty.
        $emails = $this->User->find('list', array('conditions' => array('User.email != ""'), 'fields' => 'User.email'));

        if ($this->request->is('post')) {
            $heading = $this->request->data['Newsletter']['heading'];
            $content = $this->request->data['Newsletter']['content'];

            $content = '<h1>' . $heading . '</h1>' . $content;
            $email = new CakeEmail();
            $email->from(array('no-reply@dosspirit.net' => 'The DOS Spirit Newsletter Goblins'))
            ->bcc($emails)
            ->emailFormat('html')
            ->template('newsletter', 'default')
            ->subject('The DOS Spirit ' . date('F', time()) . ' Newsletter')
            ->send($content);

            $this->Session->setFlash('Newsletter was sent!');
        }
    }

    public function profile() {
        $this->loadModel('User');

        // Profile updates.
        if ($this->request->is('put')) {
            $this->loadModel('User');
            $user['User'] = $this->Auth->user();
            $data = $this->request->data;

            // TODO: Update this
            $user['User']['location'] = $data['User']['location'];
            $user['User']['name'] = $data['User']['name'];

            // Check that there is a new avatar image present
            if ($data['avatar']['error'] == 0) {
                $avatar = $data['avatar'];

                if ($this->CommonFunctions->checkValidImageExtension($this->CommonFunctions->getFileExtension($avatar['name']))) {
                    $fileName = $this->Auth->user('slug') . '.' . $this->CommonFunctions->getFileExtension($avatar['name']);
                    if ($this->CommonFunctions->uploadFile($avatar, IMAGES . 'avatar', strtolower($fileName))) {
                        $user['User']['avatar'] = $fileName;
                    } else {
                        $this->Session->setFlash(__('Could not update avatar.'));
                    }
                }
            }

            // Turn off validation for updated fields (for now).
            if ($this->User->save($user, false)) {
                $this->Session->setFlash(__('Profile updated'));
                $this->Auth->login($user['User']);
            } else {
                $this->Session->setFlash(Set::flatten($this->User->validationErrors, ','));
            }
        }

        $this->data = array('User' => $this->Auth->user());
    }
    /**
     *
     * Edit action
     * @param action type $type
     * @param entry slug $slug
     */
    public function edit($type = null, $slug = null, $id = null) {
        if (isset($type) && !is_null($type)) {
            $pageType = $type;
        }

        switch ($pageType) {
            case 'company':
                $this->loadModel('Company');
                $companyData = $this->Company->find('first', array('conditions' => array('Company.slug' => $slug, 'Company.id' => $id)));

                $this->set('data', $companyData);
                $this->render('add_company');
            break;
            case 'review':
                $this->loadModel('Review');
                if ($slug) {
                    if ($this->request->is('post')) {
                        if ($this->Review->save($this->request->data)) {
                            $this->Session->setFlash(__('Review for %s was updated and saved.', array($slug)));

                            // Delete cache entry for this game, so all changes can be seen immediately!
                            Cache::delete('game_' . $id, 'long');
                            $this->redirect('/admin/edit/review');
                        } else {
                            $this->Session->setFlash(__('Saving of review failed.'));
                        }

                    } else {
                        $reviewData = $this->Review->find('first',
                        array('conditions' => array('Game.slug' => $slug, 'Game.id' => $id))
                        );

                        if ($reviewData) {
                            $this->data = $reviewData;
                        }

                        $this->render('edit_review');
                    }
                } else {
                    $this->paginate = array(
                        'conditions' => array('User.id' => $this->Auth->user('id')),
                        'order' => 'Review.id DESC',
                        'fields' => 'Game.name, Game.id, Game.slug, Game.focus, Review.*',
                        'limit' => 12
                    );

                    $this->set('listType', 'reviewListingEdit');
                    $data = $this->paginate('Review');
                    $this->set('data', $data);
                    $this->render('overview_list');
                }
                break;

            case 'game':
                $this->loadModel('Game');
                $this->loadModel('MediaPool');
                $this->loadModel('GameCompany');

                // Set the exotic association for the duration of the request!
                $this->Game->bindModel(array(
                	'hasMany' => array(
                		'GameCompany',
            			'GameCategory',
            			'GamePlatform',
            			'GameMode',
            	        'GameSerie',
            			'GameLog')
                ),
                false);

                // Find all active media along with the user who submitted it.
                $this->set('mediaList', $this->MediaPool->getMediaList());

                $this->loadModel('Company');
                $this->loadModel('Platform');
                $this->loadModel('Category');
                $this->loadModel('Serie');

                $this->set('companies', $this->Company->find('list', array('order' => array('Company.name ASC'))));
                $this->set('platforms' , $this->Platform->find('list', array('order' => array('Platform.name ASC'))));
                $this->set('categories' , $this->Category->find('list', array('fields' => 'id, name_english', 'order' => array('Category.name_english ASC'))));
                $this->set('series' , $this->Serie->find('list', array('order' => array('Serie.name ASC'))));

                if ($slug && $id) {
                    if ($this->Auth->user('admin') != 1) {
                        $this->Session->setFlash('Only admins can edit games.');
                        $this->redirect('/admin/dashboard');
                    }
                    if ($this->request->is('put')) {
                        $data = $this->request->data;

                        $gameId = $data['Game']['id'];
                        $gameSlug = $data['Game']['slug'];

                        $data['GameLog'][] = array('data' => 'Edited by ' . $this->Auth->user('username'));
                        $uploadResult = $this->CommonFunctions->uploadIngressAndFocusImage($data, $gameId);

                        foreach ($uploadResult as $key => $value) {
                            $data['Game'][$key] = $value;
                        }

                        if (isset($data['MediaPool']['selected'])) {
                            $data['Media'] = $this->_attachMediaFiles($data['MediaPool']['selected'], $gameId, $gameSlug);
                        }

                        if ($this->Game->saveAssociated($data)) {
                            $this->Session->setFlash(__('Game was updated.'));

                            $this->_createInboxItem('You edited the game: ' . $data['Game']['name'], $gameSlug . '/' . $gameId, 0, 'edited game');

                            // Delete cache entry for this game, so all changes can be seen immediately!
                            Cache::delete('game_' . $id, 'long');
                            Cache::delete('element_game_' . $gameId . '_medialist', 'element');
                        } else {
                            pr($this->Game->validationErrors);
                        }
                    }
                    $gameData = $this->Game->find('first',
                    array('conditions' => array('Game.slug' => $slug)));

                    $this->data = $gameData;
                    $this->render('edit_game');
                } else {
                    $this->Paginator->settings['fields'] = array();
                    //$this->Paginator->settings['conditions'] = array('Game.user_id' => $this->Auth->user('id'));
                    $this->Paginator->settings['order'] = 'Game.id DESC';

                    $data = $this->Paginator->paginate('Game');

                    $this->set('data', $data);
                    $this->set('listType', 'gameListing');
                    $this->render('overview_list');
                }
                break;
        }
    }

    /**
     *
     * Handle password resets for an account
     */
    public function reset() {
        if ($this->request->is('post')) {
            $this->loadModel('User');
            $user = $this->User->findByEmail($this->data['User']['email']);
            if ($user) {
                // Generate unique reset key
                $emailAddress = $user['User']['email'];
                $resetKey = AuthComponent::password($emailAddress . time());
                $user['User']['resetkey'] = $resetKey;
                $this->User->save($user, false, array('resetkey'));

                App::uses('CakeEmail', 'Network/Email');

                $emailContent = '<a href="http://tdscake/admin/change/email:' . $emailAddress . '/rk:' . $resetKey . '">Click here to reset your password</a>' . "\n";
                $emailContent .= 'If you cannot see the link, copy and paste this url in your browser: http://' . $_SERVER['SERVER_NAME'] . '/admin/change/email:' . $emailAddress . '/rk:' . $resetKey;
                $email = new CakeEmail();
                $email->emailFormat('html');
                $email->template('forgot_password', 'default');
                $email->from(array('account-deamon-no-reply@dosspirit.net' => 'The DOS Spirit Account Goblins'));
                $email->to($emailAddress);
                $email->subject('The DOS Spirit - Password change request');
                $email->send($emailContent);
                $this->set('resetStarted', 1);
            } else {
                $this->Session->setFlash(__('No user found with this email address'));
            }
        }
        $this->render('forgot_password');
    }

    public function change() {
        $params = $this->request->params['named'];
        if (!isset($params['email']) || !isset($params['rk'])) {
            $this->redirect('/admin/login');
        } else {
            // See if parameters are even correct
            $this->loadModel('User');
            $userData = $this->User->find('first', array(
            	'conditions' => array('User.email' => $params['email'], 'User.resetkey' => $params['rk'], 'User.resetkey !=' => '')
            ));

            if ($userData) {
                if ($this->request->is('post')) {
                    if ($this->request->data['User']['password'] == $this->request->data['User']['repeatPassword']) {
                        $postData = $this->request->data;
                        $userData['User']['password'] = $postData['User']['password'];
                        $userData['User']['resetkey'] = '';

                        if ($this->User->save($userData, true, array('password', 'resetkey'))) {
                            $this->Session->setFlash(__('Account password updated!'));
                            $email = new CakeEmail();
                            $email->emailFormat('html');
                            $email->template('password_changed', 'default');
                            $email->from(array('account-deamon-no-reply@dosspirit.net' => 'The DOS Spirit Account Goblins'));
                            $email->to($userData['User']['email']);
                            $email->subject('The DOS Spirit - Password has been changed');
                            $email->send($emailContent);
                            $this->redirect('/admin/login');
                        }
                    } else {
                        $this->Session->setFlash(__('The password must be repeated correctly twice.'));
                    }
                }
                $this->set('info', $this->request->params['named']);

            } else {
                $this->Session->setFlash(__('Invalid request data provided.'));
            }

            $this->render('reset_password');
        }
    }

    public function register()  {
        $this->RandomKey->settings = array(
        		'defaultLength' => 16,
        		'caseType' => 'mixed');

        if ($this->request->is('post')) {
            if ($this->MathCaptcha->validates($this->data['User']['security_challenge'])) {
                if (!empty($this->request->data)) {
                    $activationCode = $this->RandomKey->generate();

                    $this->request->data['UserActivationCode']['key'] = $activationCode;
                    $this->loadModel('User');
                    $this->loadModel('UserActivationCode');

                    if ($this->User->saveAssociated($this->request->data)) {

                        App::uses('CakeEmail', 'Network/Email');

                        $emailContent = $_SERVER['SERVER_NAME'] . '/admin/activate/email:' . $this->request->data['User']['email'] . '/ak:' . $activationCode;
                        $email = new CakeEmail();
                        $email->from(array('account-deamon-no-reply@dosspirit.net' => 'The DOS Spirit Account Goblins'))
                        ->to($this->request->data['User']['email'])
                        ->emailFormat('html')
                        ->template('activate_account', 'default')
                        ->subject('The DOS Spirit - New Account Registration')
                        ->send($emailContent);

                        $this->Session->setFlash('User created. Please check your inbox for activation code. Either click link in the email or paste activation code in here.', 'message_ok');
                        $this->Session->write('userEmail', $this->request->data['User']['email']);

                        $this->redirect('/admin/activate');
                    }
                }
            } else {
                $this->Session->setFlash('Please enter the correct answer to the math question', 'message_error');
            }
        }
        $this->set('mathCaptcha', $this->MathCaptcha->generateEquation());
    }

    public function activate() {
        $this->loadModel('User');

        if ($this->Session->read('userEmail')) {
            $this->set('userEmail', $this->Session->read('userEmail'));
        } else {
            $this->set('userEmail', '');
        }

        if ($this->request->is('post')) {
            $userEmail = $this->request->data['User']['email'];
            $userActivationKey = $this->request->data['UserActivationCode']['key'];
        } else if ($this->request->is('get')) {
            if (isset($this->request->params['named']['email']) && isset($this->request->params['named']['ak'])) {
                $userEmail = $this->request->params['named']['email'];
                $userActivationKey = $this->request->params['named']['ak'];
            }
        }

        if (isset($userEmail) && isset($userActivationKey)) {
            $conditions = array(
                'User.email' => $userEmail,
                'UserActivationCode.key' => $userActivationKey,
                'User.activated' => 0
            );

            $user = $this->User->find('first', array('conditions' => $conditions));

            if ($user !== false) {
                // activate the user then redirect
                $this->loadModel('UserActivationCode');
                $user['User']['activated'] = 1;

                // Update the user info
                if ($this->User->save($user)) {
                    // Remove activation code
                    $this->UserActivationCode->delete($user['UserActivationCode']);
                    // Remove all activation code info, no need to have after activation.
                    $user['UserActivationCode'] = null;
                    $this->Auth->login($user['User']);

                    $this->Session->setFlash(__('Congratulations, your user account has been activated!'));
                    $this->redirect('/admin/dashboard');
                } else {
                    $this->Session->setFlash(__('Update failed. Please send us a debug mail regarding this.'));
                }
            } else {
                // User not found
                $this->Session->setFlash('User already activated, may not exist or activation code is incorrect.');
            }
        }
    }

    public function test() {

        $this->loadModel('User');

    }

    private function _createInboxItem($activity, $link, $reward, $type) {
        $this->loadModel('UserInbox');

        $userInboxArray = array();
        $userInboxArray['UserInbox']['activity']= $activity;
        $userInboxArray['UserInbox']['link'] = $link;
        $userInboxArray['UserInbox']['reward'] = $reward;
        $userInboxArray['UserInbox']['type'] = $type;

        $this->UserInbox->saveAll(array(0 => $userInboxArray));
    }
    /**
     * Rewards a user experience points and updates the Auth object.
     * @param User $user
     * @param int $points
     */
    private function _rewardUser($userId, $points) {
        $this->loadModel('User');

        $userObject = $this->User->findById($userId, array('contain' => array(), 'fields' => 'User.id, User.experience'));

        $userObject['User']['experience'] += $points;
        //$userObject = array('User' => $this->Auth->user());

        $this->User->save($userObject, false, array('experience'));

        // Return the new user object count for that user.
        return $userObject;
    }

    public function add($pageType) {
        if (isset($pageType)) {
            $pageType = $this->passedArgs[0];
        } else {
            $this->cakeError('pageNotFound', $this->params['url']);
        }

        $this->loadModel('Company');
        $this->loadModel('Platform');
        $this->loadModel('Category');
        $this->loadModel('Serie');

        $this->set('companies', $this->Company->find('list', $conditions = array('order' => array('Company.name ASC'))));
        $this->set('platforms' , $this->Platform->find('list', array('order' => array('Platform.name ASC'))));
        $this->set('categories' , $this->Category->find('list', array('fields' => 'id, name_english', 'order' => array('Category.name_english ASC'))));
        $this->set('series' , $this->Serie->find('list', array('order' => array('Serie.name ASC'))));

        switch ($pageType) {
            case 'serie':
                if ($this->request->is('post')) {
                    $this->loadModel('Serie');
                    if (!$this->Serie->save($this->data)) {
                        $this->set('validationErrors', $this->Serie->validationErrors);
                    } else {
                        $this->_rewardUser($this->Auth->user('id'), 20);
                        $this->_createInboxItem('You added a game serie: ' . $this->data['Serie']['name'], '/search/serie/' . $this->data['Serie']['slug'], 20, 'added serie');
                        $this->Session->setFlash('The series ' . $this->data['Serie']['name'] . ' was added');
                        $this->redirect('/admin/series');
                    }
                }
                $this->render('add_serie');
            break;
            case 'company':
                if ($this->request->is('post')) {
                    if ($this->Company->save($this->request->data)) {
                        $this->_rewardUser($this->Auth->user('id'), 50);
                        $this->_createInboxItem('You added a company: ' . $this->data['Company']['name'], '#', 50, 'added company');
                        $this->Session->setFlash('The company ' . $this->data['Company']['name'] . ' was added.');
                        $this->redirect('/admin/companies');
                    } else {
                        $this->set('validationErrors', $this->Company->validationErrors);
                    }
                }
                $this->render('add_company');
            break;
            // Add new media.
            case 'media':
                if ($this->request->is('post')) {
                    $mediaPoolBlock = array();
                    $userInboxBlock = array();
                    $errors = array();
                    $accumulatedMediaPoints = 0;

                    foreach ($this->request->data['media'] as $key => $mediaEntry) {

                        // Get media type as defined
                        if (isset($this->request->data['mediatypes'][$key])) {
                            $mediaType = $this->request->data['mediatypes'][$key];
                        } else {
                            $mediaType = null;
                        }

                        // Game files are downloadable, pack them and do nothing more.
                        if ($mediaType == 'gamefile') {
                            // We should probably not change filename as it's hard to find out what kind of file it is (if no comment provided).
                            // Lowers cases, removes special chars, puts proper extensions at end after slugging.
                            $fileName = Inflector::slug(mb_strtolower($mediaEntry['name'], 'utf8'), '-') . '.' . $this->CommonFunctions->getFileExtension($mediaEntry['name']);

                            if (!$this->CommonFunctions->uploadFile($mediaEntry, IMAGES . 'mediapool', $fileName)) {
                                $errors[] = __('Could not upload file: "%s".', array($fileName));
                            } else {
                                $mediaPoolArray['MediaPool']['name'] = $fileName;
                                $mediaPoolArray['MediaPool']['created'] = date('Y-m-d H:i:s', time());
                                $mediaPoolArray['MediaPool']['active'] = 0; // Signal that it needs approval.
                                $mediaPoolArray['MediaPool']['comment'] = $this->request->data['mediacomment'][$key];
                                $mediaPoolArray['MediaPool']['type'] = $mediaType;
                                $mediaPoolBlock[] = $mediaPoolArray;

                                $userInboxArray['UserInbox']['activity']= 'Added a game file: ' . $fileName;
                                $userInboxArray['UserInbox']['link'] = $fileName;
                                $userInboxArray['UserInbox']['reward'] = $this->siteSettings['SiteSetting']['reward_media'];
                                $userInboxArray['UserInbox']['type'] = 'added media';
                                $userInboxBlock[] = $userInboxArray;
                                $accumulatedMediaPoints += $this->siteSettings['SiteSetting']['reward_media'];
                            }
                        } else {
                            if ($this->CommonFunctions->checkValidImageExtension($mediaEntry['name'])) {
                                // Generate a filename for this media that's based on the file name and tmp name.
                                $fileName = strtolower(sha1($mediaEntry['name'] . $mediaEntry['tmp_name']) . '.' . $this->CommonFunctions->getFileExtension($mediaEntry['name']));

                                if (!$this->CommonFunctions->uploadFile($mediaEntry, IMAGES . 'mediapool', $fileName)) {
                                    $errors[] = __('Could not upload file: "%s".', array($mediaEntry['name']));
                                } else {
                                    $mediaPoolArray['MediaPool']['name'] = $fileName;
                                    $mediaPoolArray['MediaPool']['created'] = date('Y-m-d H:i:s', time());
                                    $mediaPoolArray['MediaPool']['active'] = 0; // Signal that it needs approval.
                                    $mediaPoolArray['MediaPool']['comment'] = $this->request->data['mediacomment'][$key];
                                    $mediaPoolArray['MediaPool']['type'] = $mediaType;
                                    $mediaPoolBlock[] = $mediaPoolArray;

                                    $userInboxArray['UserInbox']['activity']= 'Added media file ' . $fileName;
                                    $userInboxArray['UserInbox']['link'] = $fileName;
                                    $userInboxArray['UserInbox']['reward'] = $this->siteSettings['SiteSetting']['reward_media'];
                                    $userInboxArray['UserInbox']['type'] = 'added media';
                                    $userInboxBlock[] = $userInboxArray;
                                    $accumulatedMediaPoints += $this->siteSettings['SiteSetting']['reward_media'];
                                }
                            } else {
                                $errors[] = __('The file "%s" has an invalid extension.', array($mediaEntry['name']));
                            }
                        }
                    }

                    if (count($mediaPoolBlock) > 0) {
                        $this->loadModel('MediaPool');
                        $this->loadModel('UserInbox');
                        $this->loadModel('User');
                        if ($this->MediaPool->saveAll($mediaPoolBlock)) {
                            $this->Session->setFlash(__('%s media files were added. You got %s experience points.', array(count($mediaPoolBlock), $accumulatedMediaPoints)));
                            $this->UserInbox->saveAll($userInboxBlock);

                            $this->_rewardUser($this->Auth->user('id'), $accumulatedMediaPoints);
                            $this->redirect('/admin/dashboard');
                        }
                    } else {
                        $this->set('errors', $errors);
                    }
                }

                $this->render('add_media');
                break;
            case 'news':
                if ($this->request->is('post')) {
                    $this->loadModel('NewsArticle');
                    $this->loadModel('MediaPool');

                    $newsArticleSlug = Inflector::slug(mb_convert_case($this->data['NewsArticle']['heading'], MB_CASE_LOWER, 'UTF-8'), '-');

                    if ($this->NewsArticle->save($this->data)) {
                        $media = $this->MediaPool->findById($this->request->data['MediaPool']['selected'][0], 'MediaPool.*, User.id');

                        $newsImage = $media['MediaPool']['name'];

                        $newImageName = $this->NewsArticle->id . '-' . $newsArticleSlug . '.' . $this->CommonFunctions->getFileExtension($newsImage);
                        copy(IMAGES . 'mediapool' . DS . $newsImage, IMAGES . 'news' . DS .  $newImageName);

                        $updatedObject = $this->request->data;
                        $updatedObject['NewsArticle']['id'] = $this->NewsArticle->id;
                        $updatedObject['NewsArticle']['image'] = $newImageName;

                        $this->NewsArticle->save($updatedObject, false, array('image'));

                        $this->_rewardUser($this->Auth->user('id'), $this->siteSettings['SiteSettings']['reward_news']);
                        $this->_createInboxItem('You added the news "' . $this->data['NewsArticle']['heading'], $newsArticleSlug . '/' . $this->NewsArticle->id, $this->siteSettings['SiteSetting']['reward_news'], 'added news');

                        $this->Session->setFlash('News added. You got ' . $this->siteSettings['SiteSetting']['reward_news'] . ' experience points. See your inbox for more details.');
                        $this->redirect('/admin/dashboard');
                    } else {
                        $this->Session->setFlash('Could add news item. Please report this.');
                    }
                } else {
                    $this->loadModel('MediaPool');
                    // Get all active media along with the user who submitted it.
                    $this->set('mediaList', $this->MediaPool->getMediaList());

                    $this->render('add_news');
                }
                break;
            case 'poll':
                $this->render('add_poll');
                break;
            case 'userreview':
                $this->render('add_user_review');
                break;

            case 'game':
                $this->loadModel('MediaPool');

                // Find all active media along with the user who submitted it.
                $this->set('mediaList', $this->MediaPool->getMediaList());

                if ($this->request->is('post')) {
                    $this->loadModel('Game');

                    // Remove duplicate game series
                    if (isset($this->data['GameSerie'])) {
                        $this->request->data['GameSerie'] = array_unique($this->request->data['GameSerie']);
                    } else {
                        unset($this->request->data['GameSerie']);
                    }

                    /**
                     * Very important! Bind up the models for GameMode, GameCompany,
                     * GameCategory and GamePlatform so these are saved!
                     * Set the second parameter to false to allow the bind to remain
                     * throughout the request.
                     */
                    $this->Game->bindModel(array(
                        'hasMany' => array(
                            'GameMode',
                            'GameCompany',
                            'GameCategory',
                            'GamePlatform',
                            'GameSerie',
                            'GameLog'
                            )
                            ), false);

                            $this->request->data['GameLog'][] = array('data' => 'Initial entry');

                            if ($this->Game->saveAssociated($this->request->data)) {
                                // Upload the ingress and focus image and get the results.
                                $result = $this->CommonFunctions->uploadIngressAndFocusImage($this->request->data, $this->Game->id);

                                // Update the game entry with focus and ingress image filepath data.
                                $this->Game->save($result, false, array('ingress', 'focus'));

                                if ($this->request->data['MediaPool']['selected']) {
                                    $gameSlug = mb_convert_case($this->request->data['Game']['name'], MB_CASE_LOWER, 'UTF-8');
                                    $gameSlug =  Inflector::slug($gameSlug, '-');

                                    $mediaFiles = $this->_attachMediaFiles($this->request->data['MediaPool']['selected'], $this->Game->id, $gameSlug);

                                    $this->loadModel('Media');
                                    $this->Media->saveAll($mediaFiles);
                                }

                                $this->Session->setFlash(__('%s was added.', array($this->request->data['Game']['name'])));

                                $this->_createInboxItem('You added the game ' . $this->request->data['Game']['name'], $this->Game->slug . '/' . $this->Game->id, $this->siteSettings['SiteSetting']['reward_game'], 'added game');
                                $this->_rewardUser($this->Auth->user('id'), $this->siteSettings['SiteSetting']['reward_game']);

                                $this->redirect('/admin/dashboard');
                            } else {
                                $this->Session->setFlash(__('Saving game failed.'));
                            }
                }
                $this->render('add_game');
                break;
                // ADD
            case 'review':
                $this->loadModel('Game');
                // We're editing a game
                if (isset($this->passedArgs[1])) {
                    $gameSlug = $this->passedArgs[1];

                    $gameData = $this->Game->find('first',
                    array(
                    	'conditions' => array('Game.slug' => $gameSlug),
                    ));

                    $this->set('gameName', $gameData['Game']['name']);

                    if ($this->request->is('post')) {
                        $this->request->data['Review']['game_id'] = $gameData['Game']['id'];
                        $this->request->data['Review']['user_id'] = $this->Auth->User('id');

                        //pr($this->request->data);
                        $this->loadModel('Review');

                        if ($this->Review->save($this->request->data)) {

                            $this->Session->setFlash('Review was added');

                            $this->_rewardUser($this->Auth->user('id'), $this->siteSettings['SiteSetting']['reward_review']);

                            $this->_createInboxItem('You added review for ' . $gameData['Game']['name'], $gameData['Game']['slug'] . '/' . $gameData['Game']['id'], $this->siteSettings['SiteSetting']['reward_review'], 'added review');

                            $this->redirect('/admin/dashboard');
                        } else {
                            $this->set('validationErrors', $this->Review->validationErrors);
                        }
                    } else {
                        $this->request->data['Review'] = $gameData['Review'];
                    }
                    $this->render('add_review');
                } else {
                    // Paginate all games that does not already have a review.
                    $this->paginate = array(
                        'conditions' => array('NOT EXISTS (SELECT id from review where Game.id = review.game_id)'),
                        'order' => 'Game.id DESC'
                    );

                    $this->set('listType', 'reviewListingAdd');
                    $this->set('data', $this->paginate('Game'));
                    $this->render('overview_list');
                }

                break;
        }
    }

    public function sitesettings() {
        $this->loadModel('SiteSetting');
        $this->loadModel('Game');

        $this->set('gameList', $this->Game->find('list', array('order' => 'Game.name ASC', 'fields' => 'Game.id, Game.name')));

        if ($this->request->is('put') || $this->request->is('post')) {
            $entry = $this->Game->find('first', array('contain' => array(), 'fields' => 'Game.slug', 'conditions' => array('Game.id' => $this->request->data['SiteSetting']['editors_choice_id'])));
            $this->request->data['SiteSetting']['editors_choice_slug'] = $entry['Game']['slug'];
            $this->SiteSetting->save($this->request->data);
            $this->Session->setFlash('Site settings saved.');
        } else {

            $this->data = $this->SiteSetting->getSettings();
        }
        $this->render('site_settings');
    }

    /**
     * Attaches existing media files to a title after the user has selected them.
     * @param array $mediaFilesArray
     * @param int $gameId
     * @param string $gameSlug
     */
    private function _attachMediaFiles($mediaFilesArray, $gameId, $gameSlug, $company = '') {
        $mediaArray = array();
        $moveDataBlock = array();
        foreach ($mediaFilesArray as $key => $mediaId) {
            $mediaPoolEntry = $this->MediaPool->findById($mediaId, 'MediaPool.*, User.id');

            $filenameSuffix = md5(time() + $key);
            $fileExtension = $this->CommonFunctions->getFileExtension($mediaPoolEntry['MediaPool']['name']);

            switch ($mediaPoolEntry['MediaPool']['type']) {
                case 'gamefile':
                    $newFilename = $gameId . '-' . $gameSlug . '-' . $filenameSuffix . '.zip';
                    $gamefileLocation = IMAGES . 'mediapool' . DS . $mediaPoolEntry['MediaPool']['name'];
                    $this->Zip->create(WWW_ROOT . 'gamefiles' . DS . $newFilename, array($gamefileLocation), true);
                    break;

                default:
                    $newFilename = $gameId . '-' . $gameSlug . '-' . $filenameSuffix . '.' . $fileExtension;
                    $oldPath = IMAGES . 'mediapool' . DS . $mediaPoolEntry['MediaPool']['name'];
                    $newPath = IMAGES . 'game' . DS . $newFilename;
                    // Copy the file from media pool onto game images directory
                    copy($oldPath, $newPath);

                    break;
            }

            $mediaArray['game_id'] = $gameId;
            $mediaArray['filename'] = $newFilename;
            $mediaArray['user_id'] = $mediaPoolEntry['User']['id'];
            $mediaArray['created'] = $mediaPoolEntry['MediaPool']['created'];
            $mediaArray['type'] = $mediaPoolEntry['MediaPool']['type'];
            $mediaArray['comment'] = isset($mediaPoolEntry['MediaPool']['comment']) ? $mediaPoolEntry['MediaPool']['comment'] : 'N/A';

            $moveDataBlock[] = $mediaArray;

            // Delete the entry in media pool since this image is now used.
            // TODO: Maybe remove deletion, to allow re-use.
            //$this->MediaPool->delete($mediaId);
        }
        return $moveDataBlock;
    }

    /**
     *
     * Logs a user out
     */
    public function logout() {
        $this->Session->setFlash(__('You are now logged out. See you soon!'));
        $this->redirect($this->Auth->logout());
    }
}
?>