<?php

class SiteSettingController extends AppController {
    //public $useTable = 'site_setting';
    public $scaffold;

    public function index() {

        $this->set('siteSettings', $this->SiteSetting->getSettings());


    }
}