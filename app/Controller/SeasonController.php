<?php

class SeasonController extends AppController {
    public function beforeFilter() {
        $this->layout = 'main';

        parent::beforeFilter();
    }

    public function index($season, $year) {
        if ($season && $year) {
            if (in_array($season, $this->validSeasons) && ($year <= date('Y', time()) && $year >= 2005)) {
                $this->loadModel('Game');
                $results = $this->Game->getGamesBySeason($season, $year);

                $this->set('selectedSeason', $season);
                $this->set('seasonYear', $year);
                $this->set('title_for_layout', ucfirst($season) . ' season ' . $year);

                if ($results) {
                    $this->set('data', $results);
                }
            }
        }
    }
}
