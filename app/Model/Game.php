<?php
class Game extends AppModel {
    public $useTable = 'game';

    public $cacheQueries = true;

    public $actsAs = array('Containable');

    private $_defaultFields = array('Game.name, Game.id, Game.slug, Game.description, Game.year, Game.publish_date, Game.ingress, Game.focus, Review.publish_date, Review.teaser_text, Game.visits, Game.votes_sum, Game.number_of_votes');

    public $validate = array(
     'name' => array(
        'notEmpty' => array(
             'rule' => 'notEmpty',
             'message' => 'Game name cannot be empty. Type something.',
             'required' => true
    ),
        'minimumLength' => array(
              'rule' => array('minLength', 2),
              'message' => 'Minimum length of %d characters',
    ),
        'uniqueName' => array(
            'rule' => 'isUnique',
            'message' => 'A game with this name is already in the database.'
        )
    ),
    'year' => array(
          'validYear' => array(
              'rule' => array('numeric', 4),
              'message' => 'Please enter a valid year',
              'allowEmpty' => false,
                'required' => true
    )
    ),
    'dosbox_page' => array(
          'validDosbox' => array(
              'rule' => array('numeric', 5),
              'message' => 'Please select wheter this game is compatible with dosbox or not.',
              'required' => true,
        )
    ),
    'scummvm_page' => array(
        'validScummVM' => array(
              'rule' => array('numeric', 5),
              'required' => true,
              'message' => 'Please select wheter this game is compatible with ScummVM or not.'
        )
    ),
    'license' => array(
        'validLicense' => array(
              'rule' => array('numeric', 1),
              'message' => 'Please provide a valid number for license type.',
              'required' => true
        )
    )
    );

    public $hasMany = 'Media';
    public $hasOne = array('Review');

    public $hasAndBelongsToMany = array(
        'Category' => array(
            'className' => 'Category',
            'joinTable' => 'game_category',
            'foreignKey' => 'game_id',
            'order' => 'category_id',
            'associationForeignKey'  => 'category_id',
    ),
        'Company' => array(
            'className' => 'Company',
            'joinTable' => 'game_company',
            'foreignKey' => 'game_id',
            'associationForeignKey' => 'company_id',
    ),
        'Platform' => array(
            'className' => 'Platform',
            'joinTable' => 'game_platform',
            'foreignKey' => 'game_id',
            'associationForeignKey' => 'platform_id',
    ),
        'Serie' => array(
            'className' => 'Serie',
            'joinTable' => 'game_serie',
            'foreignKey' => 'game_id',
            'associationForeignKey' => 'serie_id'
    )
    );

    public function beforeSave($options) {
        if (isset($this->data['Game']['name'])) {

            $gameSlug = mb_convert_case($this->data['Game']['name'], MB_CASE_LOWER, 'UTF-8');
            $gameSlug =  Inflector::slug($gameSlug, '-');

            // Only update the author (user) who added the game if the user id isn't present.
            // This ensures that for games that are edited by someone else than the author, the originator
            // is not removed. This is usually only in effect when editing a game.
            if (!isset($this->data['Game']['user_id'])) {
                $this->data['Game']['user_id'] = AuthComponent::user('id');
            }
            $this->data['Game']['slug'] = $gameSlug;
        }
        return true;
    }

    /**
     * Retrieves all games between two dates. Used primary for seasons, like easter or summer.
     * @param string $startDate
     * @param string $endDate
     * @return null|array
     */
    public function getGamesBySeason($season, $year, $amount = 25, $customCacheKey = 'games_by_season') {
        switch ($season) {
            case 'christmas':
                $startDate = $year . '-12-01';
                $endDate = $year . '-12-26';
            break;

            case 'winter':
                $startDate = $year . '-01-01';
                $endDate = $year . '-02-31';
            break;

            case 'easter':
                $startDate = $year . '-03-20';
                $endDate = $year . '-04-01';
            break;

            case 'spring':
                $startDate = $year . '-03-01';
                $endDate = $year . '-05-01';
            break;

            case 'summer':
                $startDate = $year . '-05-01';
                $endDate = $year . '-07-31';
            break;

            case 'halloween':
                $startDate = $year . '-10-31';
                $endDate = $year . '-11-01';
            break;

            case 'valentine':
                $startDate = $year . '-02-14';
                $endDate = $year . '-02-15';
            break;
        }

        // If year is this year or in the future, we need to check that the publish_date is passed.
        $conditions = array(
                'Game.publish_date <=' => (date('Y-m-d H:i:s', time())),
                'Game.publish_date BETWEEN ? and ?' => array($startDate, $endDate),
                'Game.active' => '1',
        );

        $conditionsArray = array(
                'conditions' => $conditions,
                'fields' => $this->_defaultFields,
                'contain' => array('Review', 'Company', 'Category'),
                'order' => 'Game.publish_date DESC',
                'limit' => $amount
        );

        $cacheKey = $customCacheKey . '_' . $season . '_' . $year;

        $results = Cache::read($cacheKey, 'short');

        if (!$results) {
            $results = $this->find('all', $conditionsArray);
            Cache::write($cacheKey, $results, 'short');
        }

        return $results;
    }
    /**
     * Retrieves latest number of games, given that their reviews are not in draft mode and published.
     * @param int $amount
     */
    public function getGames($amount = 5, $type = 'latest_games') {
        $cacheKey = $type;
        switch ($type) {
            // Gets most recent games that have a review.
            case 'latest_reviews':
                $conditionsArray = array(
                    'conditions' => array(
                        'Game.publish_date < ' => date('Y-m-d H:i:s', time()),
                        'Game.active' => '1',
                        'Review.publish_date <' => date('Y-m-d H:i:s', time())
                ),
                    'fields' => $this->_defaultFields,
                    'contain' => array('Review', 'Company', 'Category'),
                    'order' => 'Review.publish_date DESC',
                    'limit' => $amount
                );
                break;
            // Gets most recent games added regardless of they have review.
            // TODO: Currently just a list of id, name and slug
            case 'latest_games':
                $conditionsArray = array(
                    'conditions' => array(
                        'Game.publish_date < ' => date('Y-m-d H:i:s', time()),
                        'Game.active' => '1',
                ),
                    'fields' => 'Game.id, Game.name, Game.slug, Game.year, Game.focus, Game.publish_date',
                    'order' => 'Game.publish_date DESC',
                    'contain' => array('Category'),
                    'limit' => $amount
                );
                break;

            case 'popular':
                $conditionsArray = array(
                    'conditions' => array(
                        'Game.publish_date < ' => date('Y-m-d H:i:s', time()),
                        'Game.active' => '1',
                        'Review.publish_date <' => date('Y-m-d H:i:s', time())
                ),
                    'fields' => $this->_defaultFields,
                    'contain' => array('Review', 'Company', 'Category'),
                    'order' => 'Game.visits DESC',
                    'limit' => $amount
                );
        }

        $results = Cache::read($cacheKey, 'short');

        if (!$results) {
            if (!is_numeric($amount)) {
                $amount = 5;
            }
            $results = $this->find('all', $conditionsArray);

            Cache::write($cacheKey, $results, 'short');
        }

        return $results;
    }

    /**
     *
     * Retrieves a random game entry data for an active and published game.
     */
    public function getRandomGame() {
        $randomGameEntry = $this->find('first',
        array(
            'conditions' => array(
            	'Game.active' => '1',
                'Game.publish_date <' => date('Y-m-d H:i:s', time())
            ),
            'fields' => $this->_defaultFields,
            'contain' => array('Review', 'Company', 'Category', 'Serie'),
            'order' => 'rand()',
            'limit' => 1
        )
        );

        return $randomGameEntry;
    }

    /**
     *
     * Retrieves a game with lesser fields, useful for displaying a game by "game card small".
     */
    public function getGameShortVersion($gameNameSlug, $gameId, $publishState = 1) {
        if ($publishState == 'both') {
            $publishState = array(0, 1);
        }

        $gameEntry = $this->find('first',
        array(
            'conditions' => array(
                'Game.id' => $gameId,
                'Game.slug' => $gameNameSlug,
                'Game.active' => $publishState,
                'Game.publish_date <' => date('Y-m-d H:i:s', time())
        ),
            'fields' => $this->_defaultFields,
            'contain' => array('Review', 'Company', 'Category'),
            'limit' => 1
        )
        );

        return $gameEntry;
    }

    /**
     * Updates visitor count which is needed since all game entries are cached.
     * @param int $gameId
     */
    public function updateVisitCount($gameId) {
        $this->id = $gameId;
        $entry = $this->find('first', array(
                'conditions' => array('Game.id' => $gameId),
                'recursive' => -1,
                'fields' => array('Game.visits'),
                'limit' => 1
                ));

        $this->saveField('visits', $entry['Game']['visits'] + 1);
    }

    /**
     * Retrieves a game entry
     * @param string $gameNameSlug
     * @param integer $gameId
     * @param integer $publishState
     * @throws CakeException
     */
    public function getGame($gameNameSlug, $gameId, $activeState = 1) {
        $gameData = Cache::read('game_' . $gameId, 'long');

        if (!$gameData) {
            $this->bindModel(array(
                'hasOne' => array('Review'),
                'hasMany' => array(
                    'GameMode',
                    'GameReputation',
                    'GameLog' => array(
                        'order' => array('GameLog.created DESC'),
                        'limit' => 10
                        )
                    ),
            ));

            if ($activeState == 'both') {
                $activeState = array(0, 1);
            }

            $conditions = array(
               'conditions' => array(
                    'Game.id'=> $gameId,
                    'Game.slug' => $gameNameSlug,
                    'Game.active' => $activeState
            ));

            // If game is set to active, ensure that the publish date has passed.
            if ($activeState == 1) {
                $conditions['conditions']['Game.publish_date <'] = date('Y-m-d H:i:s', time());
            }

            $gameData = $this->find('first', $conditions);

            if ($gameData !== false) {
                $subArray = array();

                // Categorize each type of media into subarrays by type.
                if (!empty($gameData['Media'])) {
                    foreach ($gameData['Media'] as $mediaEntry) {
                        $subArray[$mediaEntry['type']][] = $mediaEntry;
                    }
                } else {
                    $subArray = array('screenshot' => array());
                }

                $defaultArray = array(
                    'likes' => array(),
                    'played' => array(),
                    'owns' => array(),
                    'want' => array()
                );

                if (!empty($gameData['GameReputation'])) {
                    foreach ($gameData['GameReputation'] as $rep) {
                        $gameReputationArray[$rep['type']][] = $rep;
                    }
                    $reputationArray = array_merge($defaultArray, $gameReputationArray);
                } else {
                    $reputationArray = $defaultArray;
                }

                $gameData['GameReputation'] = $reputationArray;
                $gameData['Media'] = $subArray;

                // Calculate file sizes for all media that have type "gamefile".
                if (isset($gameData['Media']['gamefile'])) {
                    foreach ($gameData['Media']['gamefile'] as &$file) {
                        $filePath = WWW_ROOT . 'gamefiles' . DS . $file['filename'];

                        if (substr(strtolower($filePath), 0, 7 ) == 'http://') {
                            $file['size'] = 'N/A (External source)';
                        } else if (file_exists($filePath)) {
                            $file['size'] = round(filesize($filePath) / 1024 / 1024, 2);
                        } else {
                            $file['size'] = 'N/A';
                        }
                    }
                }

                // TODO: In order to log visits we need this one commented out.
                Cache::write('game_' . $gameId, $gameData, 'long');
            } else {
                return;
            }
        }
        return $gameData;
    }

    public function checkIfGameExists($gameId, $gameSlug) {
        if (!empty($gameSlug)) {
            //$this->Behaviors->load('Containable');
            $conditions = array(
                'conditions' => array('Game.id' => $gameId, 'Game.slug' => $gameSlug),
                'contain' => array('Media'),
                'fields' => 'Game.*'
            );
            $gameData = $this->find('first', $conditions);

            if ($gameData) {
                return $gameData;
            }
        }
        return;
    }

    /*
     * Check if a game exists by looking for the name.
     */
    public function checkIfGameExistsByName($gameName) {
        $gameName = trim($gameName);
        $conditions = array(
            'conditions' => array('Game.name LIKE' => '%' . $gameName . '%'),
            'contain' => array(),
            'fields' => 'Game.name'
        );

        $gameData = $this->find('list', $conditions);

        if ($gameData) {
            return $gameData;
        }
    }
}