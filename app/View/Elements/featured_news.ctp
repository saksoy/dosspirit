<h1><?php echo __('Latest news'); ?></h1>
<ul class="cleanList">
<?php
    foreach ($latestNews as $news) {
        $article = $news['NewsArticle'];
        echo '<li>
        <a href="' . $selectedLanguage .'/news/' . $article['slug'] . '/' . $article['id'] . '">
        <h3>' . $article['heading'] . '</h3>' .
        '<div class="ingressContainer defaultShadow">
        <img src="/image/view/type:news/' . $article['image'] . '/600/nowatermark/cache" width="600" alt="News image" />
        </div>
        </a>
        <div class="newsTeaserText">' . $this->CommonViewFunctions->substringWholeWords($article['text'], 40) . '...</div>
        </li>';
    }
?>
<li><p><?php echo '<a href="/news/">' . __('More news') . '</a>'; ?></p></li>
</ul>