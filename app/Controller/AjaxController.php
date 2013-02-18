<?php
class AjaxController extends AppController {
    /**
     * (non-PHPdoc)
     * Everything going to this controller should be accessed by Ajax. End of story.
     * @see Controller::beforeFilter()
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->autoRender = false;
        $this->layout = false;

        if (!$this->request->is('ajax')) {
            $this->redirect('/');
        }
    }

    public function preview() {
        if ($this->request->is('ajax')) {
            $this->set('data', $this->data);
            $this->render('/Elements/ajaxreturn');
        }
    }

    /**
     * Used in conjunction when adding a game via Admin.
     */
    public function checkifgameexists() {
        if ($this->request->is('post')) {
            if ($this->request->data['name'] && mb_strlen($this->request->data['name'], 'utf-8') > 2) {
                $this->loadModel('Game');
                $name = trim($this->request->data['name']);
                $result = $this->Game->checkIfGameExistsByName($this->request->data['name']);
                
                if (!$result) {
                    echo 'No such game exists. You can safely add it.';
                } else {
                    $resultString = preg_replace('/' . $name . '/i', '<span class="green">' . $name . '</span>', implode(',', $result));
                    echo 'Found <strong>' . count($result) . '</strong> games that share similar name: ' . $resultString . '';
                }
            } else {
                echo 'Please write something first.';
            }
        }
    }


    /**
     * Various ajax game interaction handlers.
     * @throws CakeException
     */
    public function gameinteraction() {
        $this->autoRender = false;
        // Only do interactions if data is posted.
        if ($this->request->is('post')) {

            $this->loadModel('Game');
            $result = $this->Game->checkIfGameExists($this->data['gameId'], $this->data['gameSlug']);
            // Only do interactions if the game exists
            if ($result) {
                $dataSent = $this->data['dataSent'];
                $gameSlug = $this->data['gameSlug'];

                switch ($this->data['type']) {
                    case 'gameVote':
                        $voteRating = $dataSent;

                        $result['Game']['number_of_votes'] += 1;
                        $result['Game']['votes_sum'] = $result['Game']['votes_sum'] + $voteRating;

                        if ($this->Game->save($result, true, array('votes_sum', 'number_of_votes'))) {
                            echo __('You rated this title with a %s. Thanks!', array($voteRating));
                        } else {
                            echo '0';
                        }

                        break;

                    case 'addGameToCollection':
                        if ($this->Auth->user()) {
                            $userId = $this->Auth->user('id');
                            $this->loadModel('UserGameCollection');

                            if (!$this->UserGameCollection->checkIfUserHasGameInCollection($result['Game']['id'], $userId)) {
                                $saveBlock['UserGameCollection'] = array(
                                        'game_id' => $result['Game']['id'],
                                        'user_id' => $userId
                                );

                                $this->UserGameCollection->create();
                                if ($this->UserGameCollection->save($saveBlock)) {
                                    echo __('Game was added to your personal collection.');
                                } else {
                                    echo __('Adding to collection failed. Report this to bakkelun@gmail.com.');
                                }

                            } else {
                                echo __('You already have this game in your personal collection.');
                            }
                        } else {
                            echo __('You need to login before adding a game to your personal collection.');
                        }
                        break;
                    case 'gameRep':
                        switch ($dataSent) {
                            case 'likeGame':
                                $gameRepType = __('likes');
                                break;
                            case 'ownsGame':
                                $gameRepType = __('owns');
                                break;
                            case 'playedGame':
                                $gameRepType = __('played');
                                break;
                            case 'wantGame':
                                $gameRepType = __('want');
                                break;
                            default:
                                throw new CakeException(__('Not a valid game reputation type.'));
                                break;
                        }

                        $responseType = __('You marked that you %s this game.', array($gameRepType));

                        // Ensure that this user has not "liked/owned/played etc." this game before.
                        $this->loadModel('GameReputation');

                        $userId = 0;
                        if ($this->Auth->user()) {
                            $userId = $this->Auth->user('id');
                        }

                        $entry = $this->GameReputation->checkEntry($gameRepType, $result['Game']['id'], $_SERVER['SERVER_ADDR'], $userId);

                        if ($entry) {
                            $saveBlock = array();
                            $dataBlock = array();

                            $dataBlock['user_id'] = $userId;
                            $dataBlock['game_id'] = $result['Game']['id'];
                            $dataBlock['type'] = $gameRepType;
                            $saveBlock['GameReputation'] = $dataBlock;
                            $this->GameReputation->create();
                            if ($this->GameReputation->save($saveBlock)) {
                                echo $responseType;
                            } else {
                                echo '0';
                            }
                        } else {
                            echo __('You have already marked this game as "%s".', array($gameRepType));
                        }
                        break;

                }
            }

        }
    }
}