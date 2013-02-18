<?php

class NewsController extends AppController {

    public $components = array('Paginator');

    public function beforeRender() {
        $this->layout = 'main';
    }

    public function beforeFilter() {
         $this->Paginator->settings = array(
            'fields' => array('NewsArticle.id, NewsArticle.created, NewsArticle.heading, NewsArticle.image, NewsArticle.slug', 'User.username', 'User.slug'),
            'order' => array('NewsArticle.created' => 'DESC'),
            'limit' => 5,
            'maxLimit' => 10
        );

        parent::beforeFilter();
    }

    public function index() {
        $this->loadModel('NewsArticle');
        $this->set('data', $this->Paginator->paginate('NewsArticle'));
    }

    public function view($newsSlug, $newsId) {
        $this->loadModel('NewsArticle');

        $news = $this->NewsArticle->getNews($newsSlug, $newsId);

        if ($news) {
            $this->set('news', $news);
            //$this->set('neighbouringNews', $neighbouringNews);
        } else {
            throw new CakeException('No news found.');
        }
    }
}