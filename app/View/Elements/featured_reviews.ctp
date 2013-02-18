<div class="latestGames">
    <ul class="latestGamesList cleanList">
    <?php
    foreach ($data as $entry) {
        $game = $entry['Game'];
        $review = $entry['Review'];

        $categories = $entry['Category'];
        $companies = $entry['Company'];
        $categoriesArray = array();

        foreach ($categories as $category) {
            $categoriesArray[] = '<li><a href="/' . $selectedLanguage . '/search/category/' . strtolower($category['name_english']) . '">' . $category['name_english'] .'</a></li>';
        }

        $focusImageList[] = $game['focus'];

    	echo '
    	<li>
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
         	' . __('Visits') . ': ' . $game['visits'] . '<br />
    	</li>
    	';
    }
    ?>
    </ul>

    <ul class="featuredTabs horizontalNavigation">
    <?php
    foreach ($focusImageList as $focus) {
        if (!$focus) {
            $focus = 'image-not-found.png';
        }
        echo '<li><img src="/image/view/type:focus/' . $focus . '/120/nowatermark/cache" alt="focus image" /></li>';
    }
    ?>

    </ul>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $('ul.latestGamesList').slide({
    	'speed': 250,
    	'pause': 5000,
    	'anim': 'fade',
    	'tabelem': 'featuredTabs',
    	'add': 'slideshow'
    });
});
</script>