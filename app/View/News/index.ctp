<div class="contentLeft">
<h1> <?php echo __('News'); ?></h1>
<ul class="cleanList">
<?php
if ($this->Paginator) {
    foreach ($data as $article) {
        echo '<li>
        <div class="newsArticle">
            <a href="/' . $selectedLanguage . '/news/' . strtolower($article['NewsArticle']['slug']) . '/' . strtolower($article['NewsArticle']['id']) .'">
                <h2>' . $article['NewsArticle']['heading'] . '</h2>
                <img src="/images/news/' . $article['NewsArticle']['image'] . '" alt="news ingess" />
                <p><em> ' . $article['NewsArticle']['created'] . '</em></p>
                <span class="button">' . __('Read more') . '</span>
            </a>
        </div>
        </li>
        ';
    }
?>
</ul>

<?php
    echo $this->element('default_pagination_markup');
}
?>
</div>

<div class="contentRight">
<?php echo $this->element('google_adsense', array('type' => 'vertical', 'height' => 800, 'width' => 160)); ?>
</div>