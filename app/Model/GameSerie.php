<?php

class GameSerie extends AppModel {
    public $useTable = 'game_serie';

    public $belongsTo = array('Game', 'Serie');
}