<div class="previousYears" style="text-align: center;">
    <h4>Browse previous years</h4>
    <ul class="horizontalNavigation">
    <?php

    for ($i = 2005; $i <= date('Y', time()); $i++) {
        echo '<li><a href="/season/' . $selectedSeason . '/' . $i . '"><span class="button">' . $i . '</span></a></li>';
    }
    ?>
    </ul>
</div>

<h1 class="seasonHeading"><?php echo ucfirst($selectedSeason) . ' season ' . $seasonYear; ?></h1>
<?php
echo $this->Html->css('season', 'stylesheet', array('inline' => false));

if ($selectedSeason == 'christmas') {
    echo '<div class="ingressContainer defaultShadow"><img src="/images/annual-christmas-calendar.png" alt="Christmas Calendar" /></div>';
}

if (isset($data)) {
    // Display the five first results as featured.
    $latest = array_slice($data, 0, 8);
    echo $this->element('featured_reviews', array('data' => $latest));
    echo $this->element('game_listing_cards', array('gameList' => array_reverse($data)));
} else {

    if ($selectedSeason == 'christmas') {
        echo __('Too Early! The DOS Spirit Christmas Calendar is not ready yet! It launches 1st of December') . ' ' . date('Y', time());
    } else {
        echo __('Nothing here yet.');
    }
}
