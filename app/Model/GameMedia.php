<?php
class GameMedia extends AppModel {
    var $useTable = 'game_media';

    var $belongsTo = 'Media';
    var $primary = 'id';

    /*var $virtualFields = array(
        'screens' => 'Media.type = "screenshot"'
    );*/


}