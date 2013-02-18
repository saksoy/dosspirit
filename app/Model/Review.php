<?php

class Review extends AppModel {
    public $useTable = 'review';

    public $belongsTo = array('Game', 'User');

    public $cacheQueries = true;
    
    public $actsAs = array('Containable');

    public $validate = array(
        'text' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Text cannot be empty. Type something.',
                'required' => true
            )
        ),
        'game_id' => array(
            'notEmpty' => array(
                'rule' => 'numeric',
                'message' => 'Must have a game id set.',
                'required' => true
            )
        ),
        'user_id' => array(
            'notEmpty' => array(
                'rule' => 'numeric',
                'message' => 'Must have a user id set.',
                'required' => true
            )
        ),
        'created' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'allowEmpty' => false,
                'required' => true,
                'message' => 'Written date must be set',
            )
        ),
        'publish_date' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'allowEmpty' => false,
                'required' => true,
                'message' => 'Publish date must be set',
            )
        ),
        'rating' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'allowEmpty' => false,
                'required' => true,
                'message' => 'Rating must be set.',
            )
        ),
        'golden' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'allowEmpty' => false,
                'required' => true,
                'message' => 'Set Golden DOS Spirit enabled or off.'
            )
        ),
        'total' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'allowEmpty' => false,
                'message' => 'Totalt must be set',
            )
        )
    );

    public function getUserReviews($userId, $amount = 10) {
        $defaultFields = array('Game.name, Game.id, Game.slug, Game.year', 'Game.publish_date', 'Game.ingress','Game.focus', 'Review.publish_date',  'Review.teaser_text', 'Game.visits');
        $conditionsArray = array(
                'conditions' => array(
                        'Game.publish_date < ' => date('Y-m-d H:i:s', time()),
                        'Game.active' => '1',
                        'Review.user_id' => $userId,
                        'Review.publish_date <' => date('Y-m-d H:i:s', time())
                ),
                'fields' => $defaultFields,
                'order' => 'Review.publish_date DESC, Game.id DESC',
                'limit' => $amount
        );

        return $this->find('all', $conditionsArray);
    }
    /**
     * Before validate logic.
     * @see Cake/Model/Model::beforeValidate()
     */
    public function beforeValidate($options) {
        // Change the rating array to a string.
        $ratingArrayImploded = array(
                $this->data['Review']['rating']['graphics'] . ',' .
                $this->data['Review']['rating']['sound'] . ',' .
                $this->data['Review']['rating']['gameplay'] . ',' .
                $this->data['Review']['rating']['story'] . ',' .
                $this->data['Review']['rating']['difficulty'] . ',' .
                $this->data['Review']['rating']['learningcurve']
        );
        $this->data['Review']['rating'] = implode(',', $ratingArrayImploded);

        $this->data['Review']['modified'] = date('Y-m-d H:i:s', time());
        $this->data['Review']['created'] = date('Y-m-d H:i:s', time());
    }

    /**
     * Apply logic after find is done.
     * @see Cake/Model/Model::afterFind()
     */
    public function afterFind($results) {
        foreach ($results as $key => $result) {

            if (isset($result['Review']['rating'])) {
                $rating = explode(',', $result['Review']['rating']);

                $ratingArray['graphics'] = isset($rating[0]) ? $rating[0] : 'N/A';
                $ratingArray['sound'] = isset($rating[1]) ? $rating[1] : 'N/A';
                $ratingArray['gameplay'] = isset($rating[2]) ? $rating[2] : 'N/A';
                $ratingArray['story'] = isset($rating[3]) ? $rating[3] : 'N/A';
                $ratingArray['difficulty'] = isset($rating[4]) ? $rating[4] : 'N/A';
                $ratingArray['learningcurve'] = isset($rating[5]) ? $rating[5] : 'N/A';

                $results[$key]['Review']['rating'] = $ratingArray;

                if (empty($result['Review']['draft'])) {
                    $results[$key]['Review']['draft'] = 0;
                }

                if (empty($result['Review']['golden'])) {
                    $results[$key]['Review']['golden'] = 0;
                }
            }
        }
        return $results;
    }
}