<?php

class GameCategory extends AppModel {
    public $useTable = 'game_category';

    public $belongsTo = array('Game', 'Category');


}