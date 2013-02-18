<?php

class UserInbox extends AppModel {
    public $useTable = 'user_inbox';

    //public $belongsTo = array('User');

    public $actsAs = array('Containable');

    public function getInbox($userId, $amount = 10) {
        $results = $this->find('all', array(
        'conditions' => array('UserInbox.user_id' => $userId),
        'order' => 'UserInbox.created DESC',
		'limit' => $amount
        ));

        return $results;
    }

    public function beforeSave($options) {
        // If the user id has already been set, do not alter it! Only if it's not present, add the currently
        // logged in user to the mix.
        if (!isset($this->data['UserInbox']['user_id'])) {
            $this->data['UserInbox']['user_id'] = AuthComponent::user('id');
        }

        $this->data['UserInbox']['created'] = date('Y-m-d H:i:s', time());
        return true;
    }
}