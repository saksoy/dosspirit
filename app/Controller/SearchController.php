<?php
class SearchController extends AppController {
    public $components = array('Paginator');

    private $_validSearchTypes = array('category', 'platform', 'year', 'term', 'company', 'user', 'mode', 'series', 'game');
    private $_searchResultLimit = 2;

    public function beforeFilter() {
        $this->Paginator->settings = array(
            'fields' => array('Game.name', 'Game.year', 'Game.description', 'Game.ingress', 'Game.focus', 'Game.slug', 'Game.id', 'Game.visits', 'Game.votes_sum', 'Game.number_of_votes'),
            'order' => array('Game.name' => 'ASC'),
            'maxLimit' => 12,
            'limit' => 12
        );

        // Any controller using beforeFilter, need to call the parent to get components needed.
        parent::beforeFilter();
    }
    /**
     * Before render controller.
     */
    public function beforeRender() {
        $this->layout = 'search';
    }


    /**
     * Search controller.
     * @param int $type
     * @param int|string $term
     */
    public function search($type = null, $term = null, $term2 = null) {
        $type = strtolower($type);

        // Add post handler.
        if ($this->request->is('post')) {
            $term = $this->data['term'];
        }

        if (in_array($type, $this->_validSearchTypes) && !is_null($type) && !is_null($term))  {
            $result = array();

            $this->loadModel('SearchPool');

            switch ($type) {
                // Move /games to this controller instead?
                case 'game':

                break;
                case 'series':
                    $this->loadModel('Serie');
                    $this->loadModel('GameSerie');

                    $serieData = $this->Serie->find('first', array('conditions' => array('Serie.slug' => $term)));

                    $this->Paginator->settings['fields'] = array();
                    $this->Paginator->settings['conditions'] = array('Serie.slug' => $term, 'Game.active = 1', 'Game.publish_date <' => date('Y-m-d H:i:s', time()));

                    $data = $this->Paginator->paginate('GameSerie');
                    $this->SearchPool->storeSearch($type, $term);
                    $this->set('searchType', __('Game series'));
                    $this->set('searchTerm', $serieData['Serie']['name']);
                    $this->set('data', $data);

                break;
                case 'category':
                    $this->loadModel('Category');
                    $this->loadModel('GameCategory');
                    $categoryData = $this->Category->find('first', array('conditions' => array('Category.slug' => $term)));

                    $this->Paginator->settings['fields'] = array();
                    $this->Paginator->settings['conditions'] = array('Category.slug' => $term, 'Game.active = 1', 'Game.publish_date <' => date('Y-m-d H:i:s', time()));

                    $data = $this->Paginator->paginate('GameCategory');

                    $this->SearchPool->storeSearch($type, $term);
                    $this->set('searchType', __('Category'));
                    $this->set('searchTerm', $categoryData['Category']['name_english']);
                    $this->set('data', $data);

                    break;
                case 'platform':
                    $this->loadModel('Platform');
                    $this->loadModel('GamePlatform');

                    $platformData = $this->Platform->find('first', array('conditions' => array('Platform.slug' => $term)));

                    $this->SearchPool->storeSearch($type, $term);

                    if ($platformData) {
                        $this->Paginator->settings['conditions'] = array('Platform.slug' => $term, 'Game.active = 1', 'Game.publish_date <' => date('Y-m-d H:i:s', time()));

                        $data = $this->paginate('GamePlatform');

                        $this->set('searchType', __('Platform'));
                        $this->set('searchTerm', $platformData['Platform']['name']);
                        $this->set('data', $data);
                    } else {
                        $error = __('Invalid platform: "%s".', array($term));
                        $this->set('error', $error);
                    }

                    break;
                case 'year':
                    $this->loadModel('Game');
                    $this->SearchPool->storeSearch($type, $term);

                    if (!is_numeric($term)) {
                        $error = __('Year must be numeric.');
                        $this->set('error', $error);
                    } elseif (strlen($term) != 4 || ($term < 1980 || $term > date('Y', time()))) {
                        $error = __('Years must have four digits and be between year %s and %s.', array(1980, date('Y', time())));
                        $this->set('error', $error);
                    } else {
                        $this->Paginator->settings['conditions'] = array('Game.year' => $term, 'Game.active = 1', 'Game.publish_date <' => date('Y-m-d H:i:s', time()));
                        $data = $this->Paginator->paginate('Game');
                        $this->set('searchType', __('Year'));
                        $this->set('searchTerm', $term);
                        $this->set('data', $data);
                    }
                    break;
                case 'term':
                    $this->loadModel('Game');
                    $this->loadModel('NewsArticle');
                    $this->loadModel('Company');
                    $this->loadModel('Serie');
                    $minimumSearchTermLength = 2;

                    if (strlen($term) > $minimumSearchTermLength) {
                        if ($this->request->is('ajax') && mb_strlen($term, 'utf-8') > 2) {
                            $suggestionsArray = array('suggestions' => null, 'data' => null);
                            $this->autoRender = false;

                            $games = $this->Game->find('all',
                            array('conditions' => array(
                                	'Game.name LIKE' => '%' . $term . '%',
                                	'Game.active = 1',
                                	'Game.publish_date <' => date('Y-m-d H:i:s', time())
                                ),
                                'recursive' => -1,
                                'limit' => 10,
                                'order' => 'Game.name ASC',
                                'fields' => array('Game.name', 'Game.id', 'Game.year', 'Game.slug')
                            ));

                            $news = $this->NewsArticle->find('all',
                                array(
                                	'conditions' => array('NewsArticle.heading LIKE' => '%' . $term . '%'),
                            	    'recursive' => -1,
                            	    'limit' => 10,
                            	    'order' => array('NewsArticle.id DESC', 'NewsArticle.heading ASC'),
                            	    'fields' => array('NewsArticle.heading', 'NewsArticle.id', 'NewsArticle.slug')
                                )
                            );

                            $companies = $this->Company->find('all',
                                array(
                                	'conditions' => array('Company.name LIKE' => '%' . $term . '%'),
                            	    'recursive' => -1,
                            	    'limit' => 10,
                            	    'order' => array('Company.name ASC'),
                            	    'fields' => array('Company.name', 'Company.slug')
                                )
                            );

                            $series = $this->Serie->find('all',
                                array(
                                	'conditions' => array('Serie.name LIKE' => '%' . $term . '%'),
                            	    'recursive' => -1,
                            	    'limit' => 10,
                            	    'order' => array('Serie.name ASC'),
                            	    'fields' => array('Serie.name', 'Serie.slug')
                                )
                            );

        			        foreach ($games as $game) {
                                $suggestionsArray['suggestions'][] = $game['Game']['name'] . ' (Game)';
                                $suggestionsArray['data'][] = '/' . $this->selectedLanguage . '/game/' . $game['Game']['slug'] . '/' . $game['Game']['id'];
                            }

                            foreach ($series as $serieItem) {
                                $suggestionsArray['suggestions'][] = $serieItem['Serie']['name'] . ' (Game series)';
                                $suggestionsArray['data'][] = '/' . $this->selectedLanguage . '/search/series/' . $serieItem['Serie']['slug'];
                            }

                            foreach ($companies as $companyItem) {
                                $suggestionsArray['suggestions'][] = $companyItem['Company']['name'] . ' (Company)';
                                $suggestionsArray['data'][] = '/' . $this->selectedLanguage . '/search/company/' . $companyItem['Company']['slug'];
                            }

                            foreach ($news as $newsItem) {
                                $suggestionsArray['suggestions'][] = $newsItem['NewsArticle']['heading'] . ' (News)';
                                $suggestionsArray['data'][] = '/' . $this->selectedLanguage . '/news/' . $newsItem['NewsArticle']['slug'] . '/' . $newsItem['NewsArticle']['id'];
                            }

                            $sugg = json_encode($suggestionsArray['suggestions']);
                            $suggData = json_encode($suggestionsArray['data']);

                            echo '{
                            	query: \''. $term . '\',
                            	suggestions: ' . $sugg . ',
                            	data: ' . $suggData .
                            '}';
                        } else {
                            $this->SearchPool->storeSearch('freetext', $term);
                            $this->Paginator->settings['conditions'] = array('Game.name LIKE' => '%' . $term . '%', 'Game.active = 1', 'Game.publish_date <' => date('Y-m-d H:i:s', time()));
                            $data = $this->Paginator->paginate('Game');
                            $this->set('searchType', $type);
                            $this->set('searchTerm', $term);
                            $this->set('data', $data);
                        }
                    } else {
                        $error = __('Search term must be longer than %s characters.', array($minimumSearchTermLength));
                        $this->set('error', $error);
                    }
                    break;
                case 'company':
                    $this->loadModel('Company');
                    $this->loadModel('GameCompany');
                    //                      $term    $term2
                    // URL: search/company/developer/companyname
                    //$this->paginate('GameCompany');
                    if (!is_null($term2)) {
                        if (in_array(strtolower($term), array('developer', 'publisher'))) {
                            $this->SearchPool->storeSearch($type . '-' . $term, $term2);
                            $companyType = ucfirst($term);
                            $companySlug = $term2;
                            $conditionArray = array('GameCompany.company_type' => $term, 'Company.slug' => $term2, 'Game.active = 1', 'Game.publish_date <' => date('Y-m-d H:i:s', time()));
                        } else {
                            $this->set('error', __('Invalid company type: "%s".', array($term)));
                        }
                        //                      term
                        // URL: search/company/rare-ltd
                    } else {
                        $this->SearchPool->storeSearch($type, $term);
                        $companySlug = $term;
                        $companyType = __('Publisher and/or Developer');
                        $conditionArray = array('Company.slug' => $term, 'Game.active = 1', 'Game.publish_date <' => date('Y-m-d H:i:s', time()));
                    }

                    $result = $this->Company->find('first', array('conditions' => array('Company.slug' => $companySlug)));
                    $searchTerm =  $result['Company']['name'] . ' ' . __('as %s.', array($companyType));

                    $this->Paginator->settings['conditions'] = $conditionArray;

                    $data = $this->Paginator->paginate('GameCompany');

                    $this->set('searchType', __('Company'));
                    $this->set('searchTerm', $searchTerm);
                    $this->set('data', $data);

                    break;
                case 'mode':
                    break;
                case 'user':
                    break;
                    //default:
            }
        } else {
            $this->set('error', __('Invalid search type: "%s".', array($type)));
        }
    }

    public function index() {
        $this->loadModel('Platform');
        $this->loadModel('Category');
        $this->loadModel('Company');
        $this->loadModel('Serie');
        $this->loadModel('SearchPool');

        $platforms = $this->Platform->find('list', array(
            'fields' => array('name', 'slug'),
            'order' => 'name ASC'
        ));

        $categories = $this->Category->find('list', array(
            'fields' => array('name_english', 'slug'),
            'order' => 'name_english ASC'
        ));

        $companies = $this->Company->find('list', array(
            'fields' => array('name', 'slug'),
            'order' => 'name ASC'
        ));

        $series = $this->Serie->find('list', array(
            'fields' => array('name', 'slug'),
            'order' => 'name ASC'
        ));

        // Find popular terms
        $searchCloud = $this->SearchPool->getPopularTerms(600);

        $this->set('categories', $categories);
        $this->set('companies', $companies);
        $this->set('platforms', $platforms);
        $this->set('series', $series);

        $this->set('searchCloud', $searchCloud);
    }

    /*public function ajax($term = '') {

        $this->autoRender = false;
        if ($this->request->is('get')) {
            $this->loadModel('Game');
            if (mb_strlen($term, 'utf-8') > 2) {
                $games = $this->Game->find('all',
                array(
                	'conditions' => array('Game.name LIKE' => '%' . $term . '%'),
                	'recursive' => -1,
                	'limit' => 10,
                    'order' => 'Game.name ASC',
                	'fields' => array('Game.name', 'Game.id', 'Game.year', 'Game.slug')
                )
                );

                foreach ($games as $game) {
                    $suggestionsArray[] = "'" . $game['Game']['name'] . "'";
                }

                $sugg = '[' . implode(',', $suggestionsArray) . ']';

                echo '{query: \''. $term . '\', suggestions: ' . $sugg . '}';

            } else {
                echo 'Invalid search term. Minimum 3 characters.';
            }
        }
    }*/
}