<?php
class GameCompany extends AppModel {
    public $useTable = 'game_company';

    public $belongsTo = array('Game', 'Company');

}