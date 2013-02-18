<h1>Site settings</h1>
<em>Control various site wide settings</em>
<?php

$themeList = array(
	'default' => 'Default',
	'easter' => 'Easter',
	'summer' => 'Summer',
	'earthday' => 'Earth day',
	'autumn' => 'Autumn',
	'winter' => 'Winter',
	'halloween' => 'Halloween',
	'christmas' => 'Christmas'
);

echo $this->Form->create('SiteSetting', array(
	'inputDefaults' => array(
		'label' => false,
		'div' => false))
);
echo $this->Form->input('SiteSetting.id', array('type' => 'hidden'));
?>
<h5>Choose Editor's Choice (will be shown on first page)</h5>
<?php
echo $this->Form->input('SiteSetting.editors_choice_id',
    array('options' => $gameList)
);

?>
<h5>How much experience points should adding a game yield?</h5>
<?php echo $this->Form->input('SiteSetting.reward_game'); ?>
<h5>How much experience points should adding a review for a game yield?</h5>
<?php echo $this->Form->input('SiteSetting.reward_review'); ?>
<h5>How much experience points should adding new media yield (file, screenshot, music, etc.)?</h5>
<?php echo $this->Form->input('SiteSetting.reward_media'); ?>
<h5>How much experience points should adding a news item yield?</h5>
<?php echo $this->Form->input('SiteSetting.reward_news'); ?>
<h5>How much experience points should adding a new poll yield?</h5>
<?php echo $this->Form->input('SiteSetting.reward_poll'); ?>
<h5>How much experience points should accepting a new media yield?</h5>
<?php echo $this->Form->input('SiteSetting.reward_validate_media'); ?>
<h5>Site theme (Sets the theme for the entire site)</h5>
<?php
echo $this->Form->input('SiteSetting.theme',
    array('options' => $themeList));
echo $this->Form->end('Save');
?>