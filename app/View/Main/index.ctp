<div class="contentLeft">
<?php
if ($switchToCalendar) {
    echo $this->element('season_featured_review', array('data' => $seasonData));
} else {
    echo $this->element('featured_reviews',
        array('data' => $latestReviews)
    );
}

echo $this->element('google_adsense', array('type' => 'horizontal', 'height' => 90, 'width' => 620));
echo $this->element('featured_news', array('latestNews' => $latestNews));

?>


</div>
<div class="contentRight">
<?php
echo '<h3>' . __("Editor's choice") . '</h3>';
echo '<div class="editorsChoiceGameEntry">';
echo $this->element('game_card_small', array('gameData' => $editorsChoiceGame));
echo '</div>';

echo '<h3>' . __('Recently added games') . '</h3>';
?>
This list includes both featured and non-featured games.

<div class="latestGames">
<?php echo $this->element('latest_games', array('data' => $latestGames)); ?>
</div>

<?php
echo '<h3>' . __('Random game') . '</h3>';
?>
<div class="randomGameEntry">
<?php echo $this->element('game_card_small', array('gameData' => $randomGame)); ?>
</div>

<?php
echo '<h3>' . __('Most active users') . '</h3>';
echo $this->element('user_listing', array('users' => $mostActiveUsers));

echo '<h3>' . __('Newest members') . '</h3>';
echo $this->element('user_listing', array('users' => $newestUsers));

echo $this->element('google_adsense', array('type' => 'vertical', 'height' => 600, 'width' => 160));

echo '<h3>' . __('Affiliates') . '</h3>';
echo $this->element('affiliates');
?>
</div>

<div class="clearer"></div>
<?php echo '<h3>' . __('Popular games') . '</h3>';
echo $this->element('game_listing_cards', array('gameList' => $popularGames));
?>

<script type="text/javascript">
$(document).ready(function() {
    $('ul.cardList li').mouseover(function() {
        $(this).find('div.gameCardDetail').show();
    });

    $('ul.cardList li').mouseout(function() {
        $(this).find('div.gameCardDetail').hide();
    });
});
</script>