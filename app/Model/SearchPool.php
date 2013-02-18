<?php

class SearchPool extends AppModel {
    public $useTable = 'search_pool';

    /**
     * Method for saving search type, term and frequency.
     * @param string $type What type of search this is
     * @param string $term The search term
     */
    public function storeSearch($type, $term) {
        // See if this term and type already exists. If it does, we update the frequency count.
        $findResult = $this->find('first', array(
            'conditions' => array('SearchPool.term' => $term, 'SearchPool.type' => $type)
        )
        );

        // Not found, so create it.
        if (!$findResult) {
            $this->create();
            $data = array(
                'SearchPool' => array(
                    'term' => $term,
                    'type' => $type,
                    'frequency' => '1'
                )
            );
            $this->save($data);
        } else {
            $this->id = $findResult['SearchPool']['id'];
            $this->saveField('frequency', $findResult['SearchPool']['frequency'] + 1);
        }
    }
    
    public function getPopularTerms($limit) {
        $searchCloud = Cache::read('searchcloud_' . $limit, 'long');
        
        if (!$searchCloud) {
            $searchCloud = $this->find('list', array(
                    'fields' => array('term', 'frequency', 'type'),
                    'conditions' => 'frequency > ' . $limit,
                    'order' => 'frequency DESC',
                    'group' => 'term',
            ));
            
            Cache::write('searchcloud_' . $limit, $searchCloud, 'long');
        }
        
        return $searchCloud;
    }
}