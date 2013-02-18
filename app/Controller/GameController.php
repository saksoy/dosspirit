<?php
class GameController extends AppController {

    public $components = array('Paginator');
    // Pre dispatch
    public function beforeRender() {
        $this->layout = 'review';
    }

    public function index() {}

    /**
     *
     * Allows preview of a game, without it being published or active.
     * @param string $gameSlug
     * @param integer $gameId
     * @throws CakeException
     */
    public function preview($gameSlug, $gameId) {
        if ($gameSlug !== null && $gameId !== null) {
            $this->loadModel('Game');
            $this->loadModel('Media');
            $this->loadModel('User');

            $gameData = $this->Game->getGame($gameSlug, $gameId, 'both');

            // Find the attached game adder.
            $userGameConditions = array(
                        'fields' => array('id', 'username', 'slug', 'avatar'),
                        'contains' => array(),
                        'conditions' => array('User.id'=> $gameData['Game']['user_id'])
            );

            // Find the attached reviewer.
            $userReviewConditions = array(
                        'fields' => array('id', 'username', 'slug', 'avatar'),
                        'conditions' => array('User.id'=> $gameData['Review']['user_id'])
            );

            $userGame = $this->User->find('first', $userGameConditions);
            $userReview = $this->User->find('first', $userReviewConditions);

            $gameData['Game']['User'] = $userGame['User'];
            $gameData['Review']['User'] = $userReview['User'];

            $this->set('gameData', $gameData);
            $this->set('gameDeveloper', isset($gameData['Company'][0]['name']) ? $gameData['Company'][0]['name'] : '');
            $this->set('gamePublisher', isset($gameData['Company'][1]['name']) ? $gameData['Company'][1]['name'] : '');
            $this->set('previewMode', 1);

            if (date('Y-m-d', time()) > $gameData['Game']['publish_date']) {
                $publishStatus = __('This game was published on %s.', array($gameData['Game']['publish_date']));
            } else {
                $publishStatus = __('This game will be published %s. You can preview it in the mean time.', array($gameData['Game']['publish_date']));
            }

            if ($gameData['Game']['active'] == 0) {
                $activeStatus = __('This game has been set to inactive and can not be accessed by the public. You can preview it though.');
            } else {
                $activeStatus = __('This game is active and can be accessed.');
            }

            $this->set('publishStatus', $publishStatus);
            $this->set('activeStatus', $activeStatus);
            $this->set('title_for_layout', __('Preview of %s', $gameData['Game']['name'] . ' (' . $gameData['Game']['year'] . ')'));

            $this->render('view');
        } else {
            throw new CakeException(__('Goblins not found. Er, we could not dig up the game you were looking for.'));
        }
    }
    /**
     * Fetches a game with the provided ID and gamename.
     * @param string $gameName
     * @param int $gameId
     * @return array|cakeError
     */
    public function view($gameSlug = null, $gameId = null) {
        if ($gameId !== null && $gameSlug !== null) {
            $this->loadModel('Game');
            $this->loadModel('Media');
            $this->loadModel('User');

            if (!$gameData = $this->Game->getGame($gameSlug, $gameId)) {
                $this->viewPath = 'Errors';
                $this->set('errorHeading', 'Page not found');
                $this->render('general_error');
            } else {
                // Find who added the game.
                $userGameConditions = array(
                'fields' => array(
                        'id', 
                        'username', 
                        'slug', 
                        'avatar'
                ),
                'contains' => array(),
                'recursive' => -1,
                'conditions' => array('User.id'=> $gameData['Game']['user_id'])
                );

                // Find the attached reviewer.
                $userReviewConditions = array(
                'fields' => array('id', 'username', 'slug', 'avatar'),
                'contains' => array(),
                'recursive' => -1,
                'conditions' => array('User.id'=> $gameData['Review']['user_id'])
                );

                $userReview = $this->User->find('first', $userReviewConditions);
                $gameData['Review']['User'] = $userReview['User'];

                $this->set('gameData', $gameData);
                $this->set('gameDeveloper', isset($gameData['Company'][0]['name']) ? $gameData['Company'][0]['name'] : '');
                $this->set('gamePublisher', isset($gameData['Company'][1]['name']) ? $gameData['Company'][1]['name'] : '');
                $this->set('title_for_layout', __('Review of %s', $gameData['Game']['name'] . ' (' . $gameData['Game']['year'] . ')'));

                // Update visitor count
                $this->Game->updateVisitCount($gameData['Game']['id']);
            }
        }
    }

    public function random() {
        $randomGameEntry = $this->Game->find('list',
        array(
            'fields' => array('Game.id', 'Game.slug'),
            'recursive' => -1,
            'conditions' => array('Game.publish_date <' => date('Y-m-d H:i:s', time()), 'Game.active' => 1),
            'order' => 'rand()',
            'limit' => 1
        )
        );

        foreach ($randomGameEntry as $id => $name) {
            $gameId = $id;
            $gameName = $name;
        }

        $this->redirect('/' . $this->selectedLanguage . '/game/' . $gameName . '/' . $gameId, null, true);
    }

    /**
     * Retrieves all games that are active and have a publish date older than the time now.
	 *
	 * @return CakeView
     */
    public function all() {
        $this->Paginator->settings = array(
                'conditions' => array(
                    'Game.active' => 1,
                    'Game.publish_date <' => date('Y-m-d H:i:s', time())
                ),
                'fields' => array('Game.name', 'Game.year', 'Game.ingress', 'Game.publish_date', 'Game.description', 'Game.focus', 'Game.slug', 'Game.id', 'Game.visits', 'Game.votes_sum', 'Game.number_of_votes'),
                'order' => array('Game.name' => 'ASC'),
                'contain' => array(),
                'limit' => 12
        );

        $data = $this->Paginator->paginate('Game');

        $this->set('searchType' , 'catalogue');
        $this->set('searchTerm', 'all');
        $this->set('data', $data);
        $this->viewPath = 'Search';
        $this->render('search');

    }
}