<?php

echo '<span class="generalButton roundedCorner defaultShadow"><a href="/game/preview/' . $this->data['Game']['slug'] . '/' . $this->data['Game']['id'] . '" target="_blank">' . __('Preview how the game looks now') . '</a></span>';
echo $this->Form->create('Game', array(
    'inputDefaults' => array(
        'label' => false,
        'div' => false,
        'id' => false
    ),
    'hiddenField' => false,
    'type' => 'file'
    )
);

for ($i = 1980; $i <= date('Y', time()); $i++) {
    $releaseYearArray[$i] = $i;
}

$ratingArray['N/A'] = 'N/A';
for ($i = (float) 0.5; $i <= 6; $i+= 0.5) {
    $ratingArray[(string)$i] = $i;
}

$licenseArray[1] = 'Shareware';
$licenseArray[2] = 'Demo';
$licenseArray[3] = 'Abandonware';
$licenseArray[4] = 'Freeware';
$licenseArray[5] = 'Ad Supported';
$licenseArray[6] = 'Indie developed';
$licenseArray[7] = 'Other';

$gameModes['singleplayer'] = 'singleplayer';
$gameModes['multiplayer'] = 'multiplayer';
$gameModes['hotseat'] = 'hotseat';

if (isset($errors)) {
    if (is_array($errors)) {
        $errors = implode('<br />', $errors);
    }
    echo '<div class="error-message">' . $errors .'</div>';
}
?>

<h4>Game name</h4>
<?php
    echo $this->Form->input('Game.name', array('type' => 'text', 'size' => 100, 'placeholder' => __('Write the game name here')));
    echo $this->Form->input('Game.id', array('type' => 'hidden'));
    echo $this->Form->input('Game.slug', array('type' => 'hidden'));
    echo $this->Form->input('Game.user_id', array('type' => 'hidden'));
?>
<h4>Game description</h4>
<?php
    echo $this->Form->input('Game.description', array('type' => 'textarea', 'placeholder' => __('Add general game information like "this is a side scrolling platformer featuring yellow monsters". This is not the review!'), 'cols' => 100, 'rows' => 10));
?>

<div class="contentColumn">

<h4>Developer</h4>
<?php
    echo $this->Form->input('GameCompany.0.company_id', array('options' => $companies, 'selected' => $this->data['GameCompany'][0]['company_id']));
    echo $this->Form->input('GameCompany.0.company_type', array('type' => 'hidden', 'value' => 'developer'));
    echo $this->Form->input('GameCompany.0.id', array('type' => 'hidden', 'value' => $this->data['GameCompany'][0]['id']));
    echo $this->Form->input('GameCompany.0.game_id', array('type' => 'hidden', 'value' => $this->data['Game']['id']));
?>

<h4>Publisher</h4>
<?php
    echo $this->Form->input('GameCompany.1.company_id', array('options' => $companies, 'selected' => $this->data['GameCompany'][1]['company_id']));
    echo $this->Form->input('GameCompany.1.company_type', array('type' => 'hidden', 'value' => 'publisher'));
    echo $this->Form->input('GameCompany.1.id', array('type' => 'hidden', 'value' => $this->data['GameCompany'][1]['id']));
    echo $this->Form->input('GameCompany.1.game_id', array('type' => 'hidden', 'value' => $this->data['Game']['id']));
?>

<h4>Publish date</h4>

<?php
    echo $this->Form->input('Game.publish_date', array(
    	'type' => 'datetime',
        'dateFormat' => 'DMYHi',
        'minYear' => '2005',
        'timeFormat' => '24',
        'maxYear' => date('Y', time()) + 1,
        'empty' => false
    ));
?>

<h4>Is this game active (accessible)</h4>
<p><em>Activate or deactivate a game. Deactivated games cannot be accessed by the public <br />and will override any publish date, but can be previewed. Useful for emergencies.</em></p>
<?php echo $this->Form->radio('Game.active', array(0 => __('No'), 1 => __('Yes')), array('empty' => false, 'legend' => false)); ?>

<h4>Game release year</h4>
<?php
    echo $this->Form->year('Game.year', 1980, date('Y'), array('empty' => false, 'name'=>'data[Game][year]', 'orderYear' => 'asc'));
?>


<h4>Serie</h4>
<div class="serieCounter">Number of series: <span><?php echo count($this->data['Serie']); ?></span></div>
<img src="/images/icon/plus.png" class="addSerie" title="Click to add another game serie" />
<div class="gameSeries">
<?php
    if(count($this->data['GameSerie'])) {
        foreach ($this->data['GameSerie'] as $key => $gameSerie) {
            echo '<div class="serieSelect">';
            echo $this->Form->input('GameSerie.' . $key . '.serie_id', array('options' => $series, 'selected' => $gameSerie['serie_id']));
            echo $this->Form->input('GameSerie.' .$key . '.game_id', array('type' => 'hidden', 'value' => $gameSerie['game_id']));
            echo $this->Form->input('GameSerie.' .$key . '.id', array('type' => 'hidden', 'value' => $gameSerie['id']));
            echo '</div>';
        }
    } else {
        echo '<div class="serieSelect">';
        echo $this->Form->input('GameSerie.0.serie_id', array('options' => $series, 'default' => '1', 'disabled' => true));
        echo '</div>';
    }
?>
</div>

<h4>Platform</h4>
<?php
    foreach ($this->data['GamePlatform'] as $key => $gamePlatform) {
        echo $this->Form->input('GamePlatform.' . $key . '.platform_id', array('options' => $platforms, 'selected' => $gamePlatform['platform_id']));
        echo $this->Form->input('GamePlatform.' .$key . '.game_id', array('type' => 'hidden', 'value' => $gamePlatform['game_id']));
        echo $this->Form->input('GamePlatform.' .$key . '.id', array('type' => 'hidden', 'value' => $gamePlatform['id']));
    }
?>

<h4>Game mode</h4>
<img src="/images/icon/plus.png" class="addGameMode" title="Click to add another game mode" />
<div class="gameModeCounter">Number of game modes: <span><?php echo count($this->data['GameMode']); ?></span></div>
<div class="gameModes">
<?php
    foreach ($this->data['GameMode'] as $key => $gameMode) {
        echo '<div class="gameModeSelect">';
        echo $this->Form->input('GameMode.' . $key . '.mode', array('options' => $gameModes, 'selected' => $gameMode['mode']));
        echo $this->Form->input('GameMode.' .$key . '.game_id', array('type' => 'hidden', 'value' => $gameMode['game_id']));
        echo $this->Form->input('GameMode.' .$key . '.id', array('type' => 'hidden', 'value' => $gameMode['id']));
        echo '</div>';
    }
?>
</div>
<h4>Age rating</h4>
<?php
    echo $this->Form->select('age', array('all' => 'Everyone', 7 => '7 years and up', 12 => '12 years and up', 16 => '16 years and up', 18 => '18 years and up'), array('empty' => false));
?>

<h4>Licensetype</h4>
<?php
    echo $this->Form->select('license', $licenseArray, array('empty' => false));
?>
</div>

<div class="contentColumn">
    <h3>Ingress image</h3>
    <?php echo '<p>' . __('Current ingress image:') . '<br /><img src="/images/game/ingress/' . $this->data['Game']['ingress'] . '" alt="Current ingress image" width="150" /></p>'; ?>
    <em>Optimal size: 600 x 215.</em><br />
    <?php echo $this->Form->input('ingressImage', array('type' => 'file', 'name' => 'data[IngressImage]')); ?>

    <h3><?php echo __('Focus image'); ?></h3>
    <?php echo '<p>' . __('Current focus image:') . '<br /><img src="/images/game/focus/' . $this->data['Game']['focus'] . '" alt="Current focus image" width="150" /></p>'; ?>
    <em>Optimal size: 320 x 120.</em><br />
    <?php echo $this->Form->input('focusImage', array('type' => 'file', 'name' => 'data[FocusImage]')); ?>

    <h3>Compability</h3>
    <?php
        if ($this->data['Game']['dosbox_page'] > 1) {
            echo '<h4>DOSBox game id</h4>';
            echo $this->Form->input('Game.dosbox_page');
        } else {
            echo '<h4>Compatible with DOSBox</h4>';
            echo $this->Form->radio('dosbox_page', array(0 => __('No'), 1 => __('Yes')), array('empty' => false, 'legend' => false));
        }
    ?>

    <h4>Compatible with ScummVM</h4>
    <?php
        echo $this->Form->radio('scummvm_page', array(0 => __('No'), 1 => __('Yes')), array('empty' => false, 'legend' => false));
    ?>

    <h3>Categories</h3>
    <div class="categoryCounter">Number of categories: <span><?php echo count($this->data['GameCategory']); ?></span></div>
    <img src="/images/icon/plus.png" class="addCategory" title="Click to add another category" />
    <div class="gameCategories">
        <?php
        foreach ($this->data['GameCategory'] as $key => $category) {
            echo '<div class="categorySelect">';
            echo $this->Form->input('GameCategory.' .$key . '.category_id', array('options' => $categories, 'selected' => $category['category_id']));
            echo $this->Form->input('GameCategory.' .$key . '.game_id', array('type' => 'hidden', 'value' => $this->data['Game']['id']));
            echo $this->Form->input('GameCategory.' .$key . '.id', array('type' => 'hidden', 'value' => $category['id']));
            echo '</div>';
        }
        ?>
    </div>

    <h3><?php echo __('Media attached to this game'); ?> </h3>
    <?php echo $this->element('attachedMediaList', array('mediaList' => $this->data['Media'])); ?>


    <h3><?php echo __('Attach new media to this game'); ?> </h3>
    <p>Choose from media that are already uploaded. If no images are suitable or exist,
    you can skip adding images, upload them via "Add Media" in the admin section and then add them to the game.</p>
    <?php echo $this->element('mediaList', array('mediaList' => $mediaList)); ?>
</div>

<?php
echo '<p><em>' . __('You can preview the game after you have saved the changes.') . '</em></p>' .
$this->Form->end('Modify this game');
?>

<script type="text/javascript">
$(document).ready(function() {
    $('img.addGameMode').bind('click', function() {
        var counter = parseInt($('.gameModeCounter span').text());
        counter++;
        $('.gameModeCounter span').text(counter);

        var newMode = $('.gameModes div:first').clone();
        newMode.find('input[type="hidden"]').remove();
        newMode.find('select').attr('name', 'data[GameMode][' + (counter-1) + '][mode]');
        newMode.append('<img src="/images/icon/cross.png" class="removeGameMode icon" title="Click to remove this game mode" />');

        $('.gameModes').append(newMode);
    });

    $('img.addCategory').bind('click', function() {
        var categoryCounter = parseInt($('.categoryCounter span').text());
        categoryCounter++;
        $('.categoryCounter span').text(categoryCounter);

        var newCategory = $('.gameCategories div:first').clone();
        newCategory.find('input[type="hidden"]').remove();
        newCategory.find('select').attr('name', 'data[GameCategory][' + (categoryCounter-1) + '][category_id]');
        newCategory.append('<img src="/images/icon/cross.png" class="removeCategory icon" title="Click to remove this category" />');

        $('.gameCategories').append(newCategory);
    });

    $('img.addSerie').bind('click', function() {
        var serieCounter = parseInt($('.serieCounter span').text());
        if (serieCounter == 0) {
        	$('.gameSeries select').removeAttr('disabled');
        	$('.gameSeries div:first').append('<img src="/images/icon/cross.png"');
        } else {
            var newSerie = $('.gameSeries div:first').clone();
            newSerie.find('input[type="hidden"]').remove();
            newSerie.find('select').attr('name', 'data[GameSerie][' + (serieCounter) + '][serie_id]');
            newSerie.append('<img src="/images/icon/cross.png" class="removeSerie icon" title="Click to remove this serie" />');
            $('.gameSeries').append(newSerie);
        }

        serieCounter++;
        $('.serieCounter span').text(serieCounter);

    });

    $('body').on('click', 'img.removeSerie', function() {
        $(this).parent().remove();
        var serieCounter = parseInt($('.serieCounter span').text());
        serieCounter--;
        $('.serieCounter span').text(serieCounter);
    });

    $('img.removeScreenshot').live('click', function() {
        $(this).parent().remove();
        var counter = parseInt($('.screenshotCounter span').text());
        counter--;
        $('.screenshotCounter span').text(counter);
    });

    $('img.removeCategory').live('click', function() {
        $(this).parent().remove();
        var categoryCounter = parseInt($('.categoryCounter span').text());
        categoryCounter--;
        $('.categoryCounter span').text(categoryCounter);
    });

    $('img.removeGameMode').live('click', function() {
        $(this).parent().remove();
        var gameModeCounter = parseInt($('.gameModeCounter span').text());
        gameModeCounter--;
        $('.gameModeCounter span').text(gameModeCounter);
    });

    $('.modal').live('click', function() {
        $(this).hide();
    });

    $('form#GameEditForm').bind('submit', function(e) {
        var checked = $('.mediaSelect:checked').length;
        if (checked > 0) {
            if (!confirm('You have selected ' + checked + ' additional media files to attach to this game. They will be appended to this entry. Is this correct?')) {
                e.preventDefault();
            }
        }
    });

});
</script>