<?php

class GameReputation extends AppModel {
    public $useTable = 'game_reputation';

    public $belongsTo = array('Game');

    public $actsAs = array('Containable');

    public function beforeSave($options) {
        // Add date and IP to the save array block.
        $this->data['GameReputation']['date'] = date('Y-m-d H:i:s', time());
        $this->data['GameReputation']['ip'] = $_SERVER['SERVER_ADDR'];

        return true;
    }

    public function checkEntry($type, $gameId, $ip, $userId) {
        $this->contain();
        $results = $this->find('first', array(
            'conditions' => array(
            	'GameReputation.game_id' => $gameId,
            	'GameReputation.ip' => $ip,
            	'GameReputation.user_id' => $userId,
                'GameReputation.type' => $type
        )
        ));

        return !$results;
    }
}