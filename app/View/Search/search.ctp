<?php
echo '<h1>' . __('Search result') . '</h1>';
echo '<span class="button">' . $this->Html->link(__('Back to search overview'), array('controller' => 'search', 'action' => 'index')) . '</span>';
if (isset($error)) {
    echo '<div class="error">' . $error .'</div>';
} else {
    if ($this->Paginator) {
        echo '<h1>' . $this->Paginator->counter(__('Found %s results for type %s: "%s"', array('{:count}', $searchType, $searchTerm))). '</h1>';
        echo '<span class="button">' . $this->Paginator->sort('Game.name', __('Order by game name')) . '</span>';
        echo '<span class="button">' . $this->Paginator->sort('Game.publish_date', __('Order by recently added'), array('direction' => 'desc')) . '</span>';
        echo '<span class="button">' . $this->Paginator->sort('Game.year', __('Order by game year')) . '</span>';
        echo '<span class="button">' . $this->Paginator->sort('Game.number_of_votes', __('Order by number of votes'), array('direction' => 'desc')) . '</span>';
        echo '<span class="button">' . $this->Paginator->sort('Game.visits', __('Order by popularity'), array('direction' => 'desc')) . '</span>';

        echo $this->element('game_listing_cards', array('gameList' => $data));

        echo $this->element('default_pagination_markup');
    }
}
?>

<script type="text/javascript">
$(document).ready(function() {
    /*$('ul.cardList li').mouseover(function() {
        $(this).find('div.gameCardDetail').show();
    });

    $('ul.cardList li').mouseout(function() {
        $(this).find('div.gameCardDetail').hide();
    });*/
});
</script>