<script type="text/javascript">
$(document).ready(function() {
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

	$('.reviewText').markItUp(myBbcodeSettings);

	$('.modal').live('click', function() {
		$(this).hide();
	});

	$('.emoticons span').click(function() {
        emoticon = $(this).attr('title');
        $.markItUp( { replaceWith:emoticon } );
    });
});
</script>
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

if (isset($errors)) {
    if (is_array($errors)) {
        $errors = implode('<br />', $errors);
    }
    echo '<div class="error-message">' . $errors .'</div>';
}
?>
<div id="reviewPreview" class="modal"></div>
<h3>Game name</h3>
<?php

echo $this->Form->input('name', array('type' => 'text', 'size' => 70)); ?>

<h3>Review text (Norwegian)</h3>
<div class="emoticons">
    <h4>Emoticons</h4>
    <span title="[emo]lol[/emo]"><img src="/images/icon/emoticon/lol.png" /></span>
    <span title="[emo]smile[/emo]"><img src="/images/icon/emoticon/smile.png" /></span>
    <span title="[emo]surprise[/emo]"><img src="/images/icon/emoticon/surprise.png" /></span>
    <span title="[emo]tongue[/emo]"><img src="/images/icon/emoticon/tongue.png" /></span>
    <span title="[emo]unhappy[/emo]"><img src="/images/icon/emoticon/unhappy.png" /></span>
    <span title="[emo]wink[/emo]"><img src="/images/icon/emoticon/wink.png" /></span>
</div>
<?php echo $this->Form->input('Review.text', array('type' => 'textarea', 'cols' => 90, 'rows' => 10, 'class' => 'reviewText'))?>

<div class="contentColumn">
<h3>Publish date</h3>
<?php
echo $this->Form->input('Review.publish_date');
?>

<h3>Game release year</h3>
<?php echo $this->Form->select('year', $releaseYearArray, array('empty' => false)); ?>

<h3>Developer</h3>
<?php echo $this->Form->select('GameCompany[company_id]', $companies, array('name' => 'GameCompany[0][company_id]', 'empty' => false));
echo $this->Form->input('GameCompany[company_type]', array('name' => 'GameCompany[0][company_type]', 'type' => 'hidden', 'value' => 'developer'));
?>

<h3>Publisher</h3>
<?php echo $this->Form->select('GameCompany[company_id]', $companies, array('name' => 'GameCompany[1][company_id]', 'empty' => false));
echo $this->Form->input('GameCompany[company_type]', array('name' => 'GameCompany[1][company_type]', 'type' => 'hidden', 'value' => 'publisher'));
?>

<h3>Platform</h3>
<?php echo $this->Form->select('Game.platform_id', $platforms, array('empty' => false)); ?>


<h3>Age rating</h3>
<?php
echo $this->Form->select('age', array('all' => 'Everyone', 7 => '7 years', 12 => '12 years', 16 => '16 years', 18 => '18 years'), array('empty' => false));
?>

<h3>Licensetype</h3>
<?php echo $this->Form->select('license', $licenseArray, array('empty' => false)); ?>

<h3>Compability</h3>
<h4>Compatible with DOSBox</h4>
<?php
echo $this->Form->select('dosbox_page', array(0 => 'No', 1 => 'Yes'), array('empty' => false));
?>

<h4>Compatible with ScummVM</h4>
<?php
echo $this->Form->select('scummvm_page', array(0 => 'No', 1 => 'Yes'), array('empty' => false));
?>

</div>

<div class="contentColumn">

    <h3>Categories</h3>
	<div class="categoryCounter">Categories: <span>1</span></div>
	<img src="/images/icon/plus.png" class="addCategory" title="Click to add another category" />
    <div class="gameCategories">
    	<div id="category-1">
            <?php echo $this->Form->select('Category-1', $categories, array('name' => 'data[GameCategory][][category_id]', 'empty' => false)); ?>
        </div>
    </div>

    <h3>Score</h3>
    Graphics: <?php echo $this->Form->select('Review.rating.graphics', $ratingArray, array('empty' => false)); ?><br />
    Sound: <?php echo $this->Form->select('Review.rating.sound', $ratingArray, array('empty' => false)); ?><br />
    Gameplay: <?php echo $this->Form->select('Review.rating.gameplay', $ratingArray, array('empty' => false)); ?><br />
    Story: <?php echo $this->Form->select('Review.rating.story', $ratingArray, array('empty' => false)); ?><br />
    Difficulty: <?php echo $this->Form->select('Review.rating.difficulty', $ratingArray, array('empty' => false)); ?><br />
    Learning Curve: <?php echo $this->Form->select('Review.rating.learningcurve', $ratingArray, array('empty' => false)); ?><br />
    Total: <?php echo $this->Form->select('Review.total', $ratingArray, array('empty' => false)); ?>

    <h3>Golden DOS Spirit?</h3>
    <?php echo $this->Form->select('Review.golden', array(1 => 'Yes!', 0 => 'No'), array('empty' => false)); ?>
    <h3>Images </h3>
    <h4>Ingressimage</h4>
    <em>Optimal size: 600 x 215.</em><br />
    <?php echo $this->Form->input('ingressImage', array('type' => 'file', 'name' => 'data[IngressImage]')); ?>

    <h4>Focus image</h4>
    <em>Optimal size: 320 x 120.</em><br />
    <?php echo $this->Form->input('focusImage', array('type' => 'file', 'name' => 'data[FocusImage]')); ?>

    <!-- <h4>Screenshots</h4>
    <img src="/images/icon/plus.png" class="addScreenshot" title="Click to add another screenshot" />
    <div class="screenshotCounter">Screenshots: <span>1</span></div>
    <div class="gameScreenshots">
        <div id="screenshot1">
            <?php echo $this->Form->input('media', array('id' => 'screenshot1', 'type' => 'file', 'name' => 'data[Media][]')); ?>
        </div>
    </div>
    -->
</div>
<?php echo $this->Form->end('Continue'); ?>