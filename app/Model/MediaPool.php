<?php
class MediaPool extends AppModel {
    var $useTable = 'media_pool';

    var $belongsTo = 'User';
    var $primary = 'id';

    public function beforeSave($options) {
        $this->data['MediaPool']['user_id'] = AuthComponent::user('id');
        return true;
    }

    public function getMediaList() {
        $result = $this->find('all',
            array(
                'conditions' => array('MediaPool.active' => 1),
                'fields' => array('MediaPool.*', 'User.username', 'User.id'),
                'order' => array('MediaPool.created' => 'desc')
            ));
        return $result;
    }
}