<div class="latestSeasonGame">
    <?php
    if ($data) {
        $game = $data['Game'];
        $review = $data['Review'];

        $categories = $data['Category'];
        $companies = $data['Company'];
        $categoriesArray = array();

        foreach ($categories as $category) {
            $categoriesArray[] = '<li><a href="/' . $selectedLanguage . '/search/category/' . strtolower($category['name_english']) . '">' . $category['name_english'] .'</a></li>';
        }


        echo '
        	<a href="/season/christmas/' . date('Y', time()) . '" title="See the Calendar so far"><h1 class="calendarDay">Christmas Calendar Day ' . date('j', strtotime($game['publish_date'])) . '</h1></a>
            <a href="/' . $selectedLanguage . '/game/' . $game['slug'] . '/' . $game['id'] .'/"><h1>' . $game['name'] . ' (' . $game['year'] . ')</h1></a>
            <div class="subtextHeader">' .
                __('Developer') . ': <a href="/' . $selectedLanguage . '/search/company/developer/' . $companies[0]['slug'] . '">' . $companies[0]['name'] . '</a> - ' .
                __('Publisher') . ': <a href="/' . $selectedLanguage . '/search/company/publisher/' . $companies[1]['slug'] . '">' . $companies[1]['name'] . '</a> - ' .
                __('Year') . ': <a href="/' . $selectedLanguage . '/search/year/' . $game['year'] . '">' . $game['year'] . '</a>
            </div>
            <div class="ingressContainer defaultShadow">
                <a href="/' . $selectedLanguage . '/game/' . $game['slug'] . '/' . $game['id'] .'/"><img src="/images/game/ingress/' . $game['ingress'] . '" title="' . $game['name'] . '" alt="image"/></a>
            </div>
            <ul class="gameCategoriesList">' . implode('', $categoriesArray) . '</ul>
            <div class="latestGameReviewTeaser"><strong>' . date('j.M Y', strtotime($review['publish_date'])) . '</strong>: ' .  $this->CommonViewFunctions->bbCodeString($review['teaser_text']) . '...</div>
            ' . __('Visits') . ': ' . $game['visits'];
    }
    ?>
</div>