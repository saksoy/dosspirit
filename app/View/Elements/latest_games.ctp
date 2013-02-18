<ul class="cleanList cleanCardList roundedCorner" id="latestGamesList">
<?php
foreach ($data as $entry) {
    $game = $entry['Game'];
    $focusImage = !empty($game['focus']) ? $game['focus'] : 'image-not-found.png';
    echo '<li class="defaultShadow">
    <a href="/' . $selectedLanguage . '/game/' . $game['slug'] . '/' . $game['id'] . '" title="' . $game['name'] . '">
    <h5>' . $game['name'] . ' </h5>
    <img src="/image/view/type:focus/' . $focusImage . '/160/nowatermark/cache" alt="focus image" />
    </a><br />';

    foreach ($entry['Category'] as $category) {
        echo '<span class="button"><a href="/' . $selectedLanguage . '/search/category/' . $category['slug'] . '">' . $category['name_english'] . '</a></span>';
    }

    echo '</li>';
}

?>
</ul>
<span class="button"><a href="<?php echo '/' . $selectedLanguage . '/games/sort:Game.publish_date/direction:desc'; ?>">More recently added games</a></span>