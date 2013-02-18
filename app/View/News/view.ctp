<?php
//echo '<h1>' . $this->Html->link($news['NewsArticle']['heading'], $news['NewsArticle']['slug'] . '/' . $news['NewsArticle']['id'], array('controller' => 'news', 'action' => 'view')) . '</h1>';
echo '<a href="/' . $selectedLanguage . '/news/' . $news['NewsArticle']['slug'] . '/' . $news['NewsArticle']['id'] . '">
<h1>' . $news['NewsArticle']['heading'] . '</h1>
<img src="/images/news/' . $news['NewsArticle']['image'] . '" class="ingressImage" alt="news image" />
</a>
<p>' . $this->CommonViewFunctions->bbCodeString(nl2br($news['NewsArticle']['text'])) . '</p>';

echo $this->element('disqus', array('disqusCommentId' => $news['NewsArticle']['slug'] . '/' . $news['NewsArticle']['id']));

echo '<menu>';
if (!empty($neighbouringNews['prev'])) {
    echo '<li>';
    echo __('Previous') .
    $this->Html->link($neighbouringNews['prev']['NewsArticle']['heading'],
    '/news/' . $neighbouringNews['prev']['NewsArticle']['slug'] . '/' . $neighbouringNews['prev']['NewsArticle']['id']);
    echo '</li>';
}

if (!empty($neighbouringNews['next'])) {
    echo '<li>';
    echo __('Next') .
    $this->Html->link($neighbouringNews['next']['NewsArticle']['heading'],
    '/news/' . $neighbouringNews['next']['NewsArticle']['slug'] . '/' . $neighbouringNews['next']['NewsArticle']['id']);
    echo '</li>';
}
echo '</menu>';
?>