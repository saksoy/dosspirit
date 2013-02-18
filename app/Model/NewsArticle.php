<?php

class NewsArticle extends AppModel {
   public $useTable = 'news_article';
   public $primary = 'id';
   public $belongsTo = array('User');

   public function getLatestNews($amount = 5) {
       $results = $this->find('all', array(
           'order' => array('NewsArticle.created DESC, NewsArticle.id DESC'),
           'limit' => $amount,
           'fields' => array('NewsArticle.id, NewsArticle.created, NewsArticle.heading, NewsArticle.image, NewsArticle.slug, NewsArticle.text, User.username, User.slug')
       ));

       return $results;
   }

   public function getNews($newsSlug, $newsId) {
        $conditions = array(
            'conditions' => array('NewsArticle.id' => $newsId, 'NewsArticle.slug' => $newsSlug)
        );

        $result = $this->find('first', $conditions);

        return $result;
   }

   public function beforeSave($options) {
       $newsArticleSlug = mb_convert_case($this->data['NewsArticle']['heading'], MB_CASE_LOWER, 'UTF-8');
       $this->data['NewsArticle']['slug'] = Inflector::slug($newsArticleSlug, '-');
       $this->data['NewsArticle']['user_id'] = AuthComponent::user('id');

       return true;
   }
}