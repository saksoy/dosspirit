<?php

class GameMode extends AppModel {
    var $useTable = 'game_mode';

    var $belongsTo = 'game';

    /*var $hasAndBelongsToMany = array(
        'Game' => array(
            'className' => 'Game',
            'joinTable' => 'game_category',
            'foreignKey' => 'category_id',
            'associationForeignKey' => 'game_id',
            'unique' => true,
            'conditions' => '',
            'fields' => '',
            'order' => 'Game.name ASC',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
            'deleteQuery' => '',
            'insertQuery' => ''
        )
    );*/
}