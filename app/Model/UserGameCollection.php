<?php

class UserGameCollection extends AppModel {
    public $useTable = 'user_game_collection';

    public $belongsTo = array('User', 'Game');

    public $actsAs = array('Containable');

    public function beforeSave($options) {
        // Add date to the save array block.
        //$this->data['UserGameCollection']['date'] = date('Y-m-d H:i:s', time());

        return true;
    }

    public function checkIfUserHasGameInCollection($gameId, $userId) {
        $this->contain();
        $results = $this->find('first', array(
            'conditions' => array(
            	'UserGameCollection.game_id' => $gameId,
            	'UserGameCollection.user_id' => $userId,
        )
        ));

        return $results;
    }
}