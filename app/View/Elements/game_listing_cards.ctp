<?php
if (count($gameList) > 0) {
    echo '<ul class="cleanList cardList">';
    foreach ($gameList as $key => $game) {
        	if ($game['Game']['number_of_votes'] > 0 && $game['Game']['votes_sum'] > 0) {
        	    $userScore = round($game['Game']['votes_sum'] / $game['Game']['number_of_votes'], 1);
        	} else {
        	    $userScore = 0;
        	}

        echo '
        <li class="defaultShadow roundedCorner"> 
        <div class="gameCard">
        	<a href="/' . $selectedLanguage . '/game/' . $game['Game']['slug'] . '/' . $game['Game']['id']. '">
        	<h5>' . $game['Game']['name'] .' (' . $game['Game']['year'] . ') </h5>
        	<img src="/image/view/type:focus/' . $game['Game']['focus'] . '/320" alt="' . __('Focus image of %s', array($game['Game']['name'])) .'" border="0" title="' . $game['Game']['name'] . '" />
        	<span class="roundedCorner overlay bottomRight">' . __('User rating: %s', array($userScore)) . ' (' . __('%s votes', array($game['Game']['number_of_votes'])) . ')</span>
        	<span class="roundedCorner overlay bottomLeft">' .__('Visits: %s', array($game['Game']['visits'])) . '</span>
        	<div class="gameCardDetail hidden">
                <div class="gameDescription">
                <h5>' .  $game['Game']['name'] . '</h5>';
                    if (isset($game['Review']['teaser_text'])) {
                        echo $game['Review']['teaser_text'];
                    } else {
                        echo $game['Game']['description'];
                    }
                echo '
                </div>
        	</div>
        	</a>
        </div>
        </li>';
    }
    echo '</ul>';
} else {
    echo '<h2>' . __('Nothing to display. Perhaps try a different search word or type?') . '</h2>';
}
?>