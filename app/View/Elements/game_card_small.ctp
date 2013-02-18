<?php
echo '<h4>' . $gameData['Game']['name'] . ' (<a href="/' . $selectedLanguage . '/search/year/' . $gameData['Game']['year'] . '">' . $gameData['Game']['year'] . '</a>)</h4>';
echo '<div class="subtextHeader">' .
__('Developer') . ': <a href="/' . $selectedLanguage . '/search/company/developer/' . $gameData['Company'][0]['slug'] . '">' . $gameData['Company'][0]['name'] . '</a> - ' .
__('Publisher') . ': <a href="/' . $selectedLanguage . '/search/company/publisher/' . $gameData['Company'][1]['slug'] . '">' . $gameData['Company'][1]['name'] . '</a>
</div>
<div class="focusContainer defaultShadow">
<a href="/game/' . $gameData['Game']['slug'] . '/' . $gameData['Game']['id'] . '"><img src="/image/view/type:focus/' . $gameData['Game']['focus'] . '/320/nowatermark/cache" alt="random game!" width="320" /></a>
</div>
<ul class="gameCategoriesList">';
foreach ($gameData['Category'] as $category) {
    echo '<li><a href="/' . $selectedLanguage . '/search/category/' . $category['slug'] . '">' . $category['name_english'] . '</a></li>';
}

echo '</ul>
<div class="gameReviewTeaser">' . $gameData['Review']['teaser_text'] . '</div>';
