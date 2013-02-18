<?php

class SiteSetting extends AppModel {
    public $useTable = 'site_setting';

    //public $belongsTo = array('User');

    public $actsAs = array('Containable');

    public function getSettings() {
        $cacheKey = 'sitesettings';
        $results = Cache::read($cacheKey, 'long');

        if (!$results) {
            $results = $this->find('first');
            Cache::write($cacheKey, $results, 'long');
        }

        return $results;
    }
}