<?php

class GamePlatform extends AppModel {
    public $useTable = 'game_platform';

    public $belongsTo = array('Game', 'Platform');
}