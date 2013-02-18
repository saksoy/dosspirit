<?php
echo $this->Form->create('Game', array(
    'inputDefaults' => array(
        'label' => false,
        'div' => false
    ),
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

$gameModes['singleplayer'] = 'Singleplayer';
$gameModes['multiplayer'] = 'Multiplayer';
$gameModes['hotseat'] = 'Hot Seat';

if (isset($errors)) {
    if (is_array($errors)) {
        $errors = implode('<br />', $errors);
    }
    echo '<div class="error-message">' . $errors .'</div>';
}
?>

<h3>Game name</h3>
<?php
    echo $this->Form->input('Game.name', array('type' => 'text', 'size' => 100, 'placeholder' => __('Write the game name here')));
?>

<div id="checkGameNameResponse"></div>

<h3>Game description</h3>
<?php
    echo $this->Form->input('Game.description', array('type' => 'textarea', 'placeholder' => __('Add general game information like "this is a side scrolling platformer featuring yellow monsters". This is not the review! You can add reviews to a game from the admin section, after the game entry has been added.'), 'cols' => 100, 'rows' => 10));
?>


<div class="contentColumn">
<h3>Developer</h3>
<?php
    echo $this->Form->input('GameCompany.0.company_id', array('options' => $companies));
    echo $this->Form->input('GameCompany.0.company_type', array('type' => 'hidden', 'value' => 'developer'));
?>

<h3>Publisher</h3>
<?php
    echo $this->Form->input('GameCompany.1.company_id', array('options' => $companies));
    echo $this->Form->input('GameCompany.1.company_type', array('type' => 'hidden', 'value' => 'publisher'));
?>

<h3>Publish date</h3>
<?php
    echo $this->Form->input('Game.publish_date', array(
    	'type' => 'datetime',
        'dateFormat' => 'DMY',
        'timeFormat' => '24',
        'selected' => date('Y-m-d 12:00', time()),
        'minYear' => date('Y', time()),
        'maxYear' => date('Y', time()) + 1,

    ));
?>

<h3>Is this game active (accessible)</h3>
<p><em>Activate or deactivate a game. Deactivated games cannot be accessed by the public <br />and will override any publish date, but can be previewed. Useful for emergencies.</em></p>
<?php echo $this->Form->radio('Game.active', array(0 => __('No'), 1 => __('Yes')), array('empty' => false, 'default' => '1')); ?>

<h3>Game release year</h3>
<?php
    echo $this->Form->select('Game.year', $releaseYearArray, array('empty' => false));
?>


<h3>Platform</h3>
<?php
    echo $this->Form->select('GamePlatform..platform_id', $platforms, array('empty' => false));
?>

<h3>Game Serie</h3>
<img src="/images/icon/plus.png" class="addSerie" title="Click to add another game serie" />
<div class="serieCounter">Number of game series: <span>0</span></div>
<div class="gameSeries">
    <div class="gameSerieSelect"><?php  echo $this->Form->input('GameSerie.0.serie_id', array('options' => $series, 'default' => '1', 'disabled' => true)); ?></div>
</div>

<h3>Game mode</h3>
<img src="/images/icon/plus.png" class="addGameMode" title="Click to add another game mode" />
<div class="gameModeCounter">Number of game modes: <span>1</span></div>
<div class="gameModes">
    <div class="gameModeSelect"><?php echo $this->Form->input('GameMode..mode', array('options' => $gameModes)); ?></div>
</div>

<h3>Age rating</h3>
<?php
    echo $this->Form->select('age', array('all' => 'Everyone', 7 => '7 years and up', 12 => '12 years and up', 16 => '16 years and up', 18 => '18 years and up'), array('empty' => false));
?>

<h3>License type</h3>
<?php
    echo $this->Form->select('license', $licenseArray, array('empty' => false));
?>
</div>

<div class="contentColumn">
    <h3>Ingress image</h3>
    <em>Optimal size: 600 x 215.</em><br />
    <?php echo $this->Form->input('ingressImage', array('type' => 'file', 'name' => 'data[IngressImage]')); ?>

    <h3>Focus image</h3>
    <em>Optimal size: 320 x 120.</em><br />
    <?php echo $this->Form->input('focusImage', array('type' => 'file', 'name' => 'data[FocusImage]')); ?>

    <h3>Compability</h3>
    <h4>Compatible with DOSBox</h4>
    <?php
        echo $this->Form->select('dosbox_page', array(0 => __('No'), 1 => __('Yes')), array('empty' => false, 'legend' => false));
    ?>

    <h4>Compatible with ScummVM</h4>
    <?php
        echo $this->Form->select('scummvm_page', array(0 => __('No'), 1 => __('Yes')), array('empty' => false, 'legend' => false));
    ?>

    <h3>Categories</h3>
    <div class="categoryCounter">Categories: <span>1</span></div>
    <img src="/images/icon/plus.png" class="addCategory" title="Click to add another category" />
    <div class="gameCategories">
        <div id="categories">
        <?php
            echo $this->Form->input('GameCategory..category_id', array('options' => $categories, 'empty' => false));
        ?>
        </div>
    </div>

    <h3><?php echo __('Attach new media to this game'); ?> </h3>
    <p>Choose from media that are already uploaded. If no images are suitable or exist,
    you can skip adding images, upload them via "Add Media" in the admin section and then add them to the game.</p>
    <?php echo $this->element('mediaList', array('mediaList' => $mediaList)); ?>
</div>
<?php
echo '<p><em>' . __('You can preview the game after you have saved the changes.') . '</em></p>' .
$this->Form->end('Add this game');
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


    $('img.addScreenshot').bind('click', function() {
        var counter = parseInt($('.screenshotCounter span').text());
        counter++;
        $('.screenshotCounter span').text(counter);

        var newFile = $('.gameScreenshots div:first').clone();
        newFile.attr('id', 'screenshot'+counter);
        newFile.append('<img src="/images/icon/cross.png" class="removeScreenshot" title="Click to remove this screenshot" />');
        $('.gameScreenshots').append(newFile);
    });

    $('img.addCategory').bind('click', function() {
        var categoryCounter = parseInt($('.categoryCounter span').text());
        categoryCounter++;
        $('.categoryCounter span').text(categoryCounter);

        var newCategory = $('.gameCategories div:first').clone();
        newCategory.attr('id', 'category-' + categoryCounter);
        newCategory.append('<img src="/images/icon/cross.png" class="removeCategory" title="Click to remove this category" />');

        $('.gameCategories').append(newCategory);
    });

    $('img.addSerie').bind('click', function() {
        var serieCounter = parseInt($('.serieCounter span').text());

        // The very first time you click, enable the category.
        if (serieCounter == 0) {
        	$('.gameSeries select').removeAttr('disabled');
        	$('.gameSeries div:first').append('<img src="/images/icon/cross.png"');
        } else {
        	var newSerie = $('.gameSeries div:first').clone();
            newSerie.attr('id', 'serie-' + serieCounter);
            newSerie.find('select').attr('name', 'data[GameSerie][' + (serieCounter) + '][serie_id]');
            newSerie.append('<img src="/images/icon/cross.png" class="removeSerie" title="Click to remove this game serie" />');
            $('.gameSeries').append(newSerie);
        }

        serieCounter++;
        $('.serieCounter span').text(serieCounter);
    });

    $('body').on('click', 'img.removeScreenshot', function() {
        $(this).parent().remove();
        var counter = parseInt($('.screenshotCounter span').text());
        counter--;
        $('.screenshotCounter span').text(counter);
    });

    $('body').on('click', 'img.removeCategory', function() {
        $(this).parent().remove();
        var categoryCounter = parseInt($('.categoryCounter span').text());
        categoryCounter--;
        $('.categoryCounter span').text(categoryCounter);
    });

    $('body').on('click', 'img.removeSerie', function() {
        $(this).parent().remove();
        var serieCounter = parseInt($('.serieCounter span').text());
        serieCounter--;
        $('.serieCounter span').text(serieCounter);
    });

    $('body').on('click', '.modal', function() {
        $(this).hide();
    });

    $('input#GameName').keyup(function() {
        if ($(this).val().length > 2) {
        	$.ajax({
                url: '/ajax/checkifgameexists',
                type: 'post',
                data: { name: $('input#GameName').val() },
                success: function(data) {
                    $('div#checkGameNameResponse').html(data);
                }
            });
        }
    });

    $('form#GameAddForm').bind('submit', function(e) {
        var checked = $('.mediaSelect:checked').length;
        if (checked > 0) {
            if (!confirm('You have selected ' + checked + ' media files to attach to this game. Is this correct?')) {
                e.preventDefault();
            }
        }
    });
});
</script>