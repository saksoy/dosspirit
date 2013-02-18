<h1><?php echo __('Search engine'); ?></h1>

<?php echo $this->element('google_adsense', array('type' => 'horizontal', 'height' => 90, 'width' => 768)); ?>


<div class="searchCloud">

    <?php
    /*pr($searchCloud); 
    foreach ($searchCloud as $type => $term);
    echo '<a href='*/
    
    ?>

</div>

<div class="message"><?php echo __('Choose your search type below'); ?></div>
<?php
   
    echo '<div class="searchTypeContainer">';
    echo '<h2>' . __('Category') . '</h2>
    <ul class="searchListCategory verticalNavigation">';
    foreach ($categories as $categoryName => $slug) {
        echo '<li>' . $this->Html->link($categoryName, '/' . $selectedLanguage . '/search/category/' . $slug, array('class' => 'searchCategoryItem')) . '</li>';
    }
    echo '</ul>
    </div>';

    echo '<div class="searchTypeContainer">';
    echo '<h2>' . __('Platform') . '</h2>
    <ul class="searchListCategory verticalNavigation">';
    foreach ($platforms as $platformName => $platformSlug) {
        echo '<li>' . $this->Html->link($platformName, '/' . $selectedLanguage . '/search/platform/' . $platformSlug, array('class' => 'searchCategoryItem')) . '</li>';
    }
    echo '</ul>
    </div>';

    echo '<div class="searchTypeContainer">';
    echo '<h2>' . __('Company') . '</h2>
    <ul class="searchListCategory verticalNavigation">';
    foreach ($companies as $companyName => $companySlug) {
        echo '<li>' . $this->Html->link($companyName, '/' . $selectedLanguage . '/search/company/' . $companySlug, array('class' => 'searchCategoryItem')) . '</li>';
    }
    echo '</ul>
    </div>';
    
    echo '<div class="searchTypeContainer">';
    echo '<h2>' . __('Game series') . '</h2>
    <ul class="searchListCategory verticalNavigation">';
    foreach ($series as $serieName => $serieSlug) {
        echo '<li>' . $this->Html->link($serieName, '/' . $selectedLanguage . '/search/series/' . $serieSlug, array('class' => 'searchCategoryItem')) . '</li>';
    }
    echo '</ul>
    </div>';

    echo '<div class="searchTypeContainer">';
    echo '<h2>' . __('Year') . '</h2>
    <ul class="searchListCategory verticalNavigation">';
    for ($i = date('Y', time()); $i > 1980 ; $i--) {
         echo '<li>' . $this->Html->link($i, '/' . $selectedLanguage . '/search/year/' . $i, array('class' => 'searchCategoryItem')) . '</li>';
    }
    echo '</ul>
    </div>';
?>