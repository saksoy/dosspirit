<?php
if (count($gameList) > 0) {
    echo '<ul class="cleanList cardList">';
    foreach ($gameList as $key => $game) {
        	if ($game['Game']['number_of_votes'] > 0 && $game['Game']['votes_sum'] > 0) {
        	    $userScore = round($game['Game']['votes_sum'] / $game['Game']['number_of_votes'], 1);
        	} else {
        	    $userScore = 0;
        	}

        echo '<li class="defaultShadow roundedCorner">
        <a href="/' . $selectedLanguage . '/game/' . $game['Game']['slug'] . '/' . $game['Game']['id'] . '">
        <h5>' . $game['Game']['name'] .' (' . $game['Game']['year'] . ') </h5>
        <img src="/image/view/type:focus/' . $game['Game']['focus'] . '/320" alt="' . __('Focus image of %s', array($game['Game']['name'])) .'" border="0" title="' . $game['Game']['name'] . '" />
        <div class="gameScore">' . __('User rating') . ': ' . $userScore  . '
         (' . __('%s votes', array($game['Game']['number_of_votes'])) . ')</div>
        <div class="gameVisits">' . __('Visits: %s', array($game['Game']['visits'])) . '</div>
        </a>
        </li>';
    }
    echo '</ul>';

    echo $this->element('default_pagination_markup');
} else {
    echo '<h2>' . __('Nothing to display. Perhaps try a different search word or type?') . '</h2>';
}
?>