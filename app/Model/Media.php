<?php
class Media extends AppModel {
    public $useTable = 'media';

    public $belongsTo = array('Game', 'User');
    public $primary = 'id';

    /**
     *
     * Retrieves all media types based upon game id and type.
     * @param int $gameId
     * @param string $mediaType
     * @return Ambigous <multitype:, NULL, mixed>
     */
    public function getMedia($gameId, $mediaType) {
        return $this->find('all', array('conditions' => array('Media.game_id' => $gameId, 'Media.type' => $mediaType)));
    }

    public function getMediaFile($mediaId, $gameId) {
        return $this->find('first', array(
        	'conditions' => array(
        		'Media.id' => $mediaId,
        		'Media.game_id' => $gameId
            )
        ));
    }
}