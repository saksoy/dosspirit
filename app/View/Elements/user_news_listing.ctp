<ul class="cleanList">

<?php
if (count($newsList) > 0) {
    foreach ($newsList as $news) {
        echo '<li><a href="/' . $selectedLanguage . '/news/' . $news['slug'] . '/' . $news['id'] . '">' . $news['heading'] . '</a></li>';
    }
} else {
    echo '<li>' . __('This user has not added any news yet.') . '</li>';
}
?>
</ul>
