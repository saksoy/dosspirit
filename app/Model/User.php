<?php

App::uses('AuthComponent', 'Controller/Component');

class User extends AppModel {
    public $useTable = 'user';
    public $hasOne = 'UserActivationCode';
    public $cacheQueries = true;
    public $actsAs = array('Containable');
    private $_defaultFields = array('User.username, User.slug, User.avatar, User.id', 'User.experience', 'User.created');

    public $validate = array(
        'username' => array(
            'alphaNumeric' => array(
                'rule' => 'alphaNumeric',
                'required' => true,
                'allowEmpty' => false,
                'required' => true,
                'message' => 'Alphabets and numbers only'
            ),
            'between' => array(
                'rule' => array('between', 5, 20),
                'message' => 'Username must be between 5 to 20 characters'
            ),
            'isUnique'=> array(
                'rule' => 'isUnique',
                'message' => 'This username has already been taken.'
            )
        ),
        'password' => array(
            'rule' => array('minLength', '6'),
            'message' => 'Password must be minimum 6 characters long',
            'required' => true
        ),
        'email' => array(
            'email',
            'isUnique' => array(
                'rule' => 'isUnique',
                'message' => 'This email is already used.'
            ))
    );

    public function beforeSave($options) {
        if (isset($this->data['User']['username'])) {
            $usernameSlug = mb_convert_case($this->data['User']['username'], MB_CASE_LOWER, 'UTF-8');
            $usernameSlug =  Inflector::slug($usernameSlug, '-');
            $this->data['User']['slug'] = $usernameSlug;
        }

        if (isset($this->data['User']['password'])) {
            $this->data['User']['password'] = AuthComponent::password($this->data['User']['password']);
        }

        return true;
    }

    public function getUsers($amount = 5, $type = 'mostActive') {
        switch ($type) {
            case 'mostActive':
                $conditionsArray = array(
                'conditions' => array('User.activated' => 1),
                'order' => array('User.experience DESC'),
                'contains' => array(),
                'fields' => $this->_defaultFields,
                'limit' => $amount
            );
            break;

            case 'newest':
                $conditionsArray = array(
                'conditions' => array('User.activated' => 1),
                'order' => array('User.created DESC'),
                'contains' => array(),
                'fields' => $this->_defaultFields,
                'limit' => $amount
            );
            break;
        }

        $result = $this->find('all', $conditionsArray);

        return $result;
    }

    /**
     * Retrieves a user's activity including news articles, added games and written reviews.
     * @param string $userSlug
     * @param int $userId
     * @return array|null
     */
    public function getActivity($userSlug, $userId, $limit = 10) {
        // Set the exotic association for the duration of the request!
        $this->bindModel(array(
            'hasMany' => array(
                'NewsArticle' => array(
                    'className' => 'NewsArticle',
                    'order' => 'NewsArticle.id DESC',
                    'fields' => 'NewsArticle.id, NewsArticle.slug, NewsArticle.heading, NewsArticle.created, NewsArticle.image',
                    'limit' => $limit
                ),
                'Game' => array(
                    'className' => 'Game',
                    'order' => 'Game.id DESC',
                    'conditions' => array('Game.publish_date <' => date('Y-m-d H:i', time()), 'Game.active = 1'),
                    'fields' => 'Game.id, Game.slug, Game.name, Game.visits, Game.focus, Game.year',
                    'limit' => $limit
                )
        )),
        false);

        $conditionsArray = array(
            'conditions' => array('User.activated' => 1, 'User.slug' => $userSlug, 'User.id' => $userId),
            'order' => array('User.created DESC'),
            'fields' => $this->_defaultFields
        );

        $user = $this->find('first', $conditionsArray);

        if ($user) {
            return $user;
        } else {
            return;
        }
    }
}