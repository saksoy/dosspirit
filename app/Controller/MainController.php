<?php

class MainController extends AppController {
    public $cacheAction = '+1 hour';

    public function index() {
        $this->layout = 'main';

        $this->loadModel('Game');
        $this->loadModel('User');
        $this->loadModel('NewsArticle');
        $this->loadModel('SiteSetting');

         $this->loadModel('Game');

        // Latest games are those that have been added, but don't have a review.
        $latestGames = $this->Game->getGames(6, 'latest_games');

        $popularGames = $this->Game->getGames(9, 'popular');
        $randomGame = $this->Game->getRandomGame();

        $editorsChoiceGame = $this->Game->getGameShortVersion($this->siteSettings['SiteSetting']['editors_choice_slug'], $this->siteSettings['SiteSetting']['editors_choice_id']);

        $mostActiveUsers = $this->User->getUsers(10, 'mostActive');
        $newestUsers = $this->User->getUsers(10, 'newest');
        $latestNews = $this->NewsArticle->getLatestNews(6);

        $switchToCalendar = false;

        if ($this->theme == 'Christmas') {
            // Get the latest published game in Christmas season this year.
            $latestSeasonGame = $this->Game->getGamesBySeason('christmas', date('Y', time()), 1, 'newest');
            if (isset($latestSeasonGame[0])) {
                $switchToCalendar = true;
                $this->set('seasonData', $latestSeasonGame[0]);
            }
        }

        if (!$switchToCalendar) {
            // Latest reviews are those games that have been added and have an attached review to them
            $latestReviews  = $this->Game->getGames(5, 'latest_reviews');
            $this->set('latestReviews', $latestReviews);
        }

        $this->set('switchToCalendar', $switchToCalendar);

        $this->set('latestGames', $latestGames);

        $this->set('popularGames', $popularGames);
        $this->set('randomGame', $randomGame);
        $this->set('editorsChoiceGame', $editorsChoiceGame);

        $this->set('latestNews', $latestNews);

        $this->set('title_for_layout', 'Spel fr&aring; dei gamle dagane, Games from ye olde times');
        $this->set('mostActiveUsers', $mostActiveUsers);
        $this->set('newestUsers', $newestUsers);
    }
}