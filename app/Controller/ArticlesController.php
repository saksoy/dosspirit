<?php

class ArticlesController extends AppController {

    public function beforeRender() {
        $this->layout = 'main';
    }

    public function index() {

    }

    public function support() {
        $this->set('title_for_layout', 'Support and donations');
    }

    public function faq() {
        $this->set('title_for_layout', 'Faq');
    }

    public function legal() {
        $this->set('title_for_layout', 'Legal disclaimer');
    }

    public function link() {
        $this->set('title_for_layout', 'Link to us');
    }

    public function emulator() {
        $this->set('title_for_layout', 'Emulators');
    }
    
    public function history() {
        $this->set('title_for_layout', 'The history of The DOS Spirit');
    }
}