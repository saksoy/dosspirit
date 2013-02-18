<?php

class GameLog extends AppModel {
    public $useTable = 'game_log';

    public $belongsTo = array('Game', 'User');

    public $hasOne = array('User');

    public function beforeSave($options) {
        $this->data['GameLog']['user_id'] = AuthComponent::user('id');
        return true;
    }


}