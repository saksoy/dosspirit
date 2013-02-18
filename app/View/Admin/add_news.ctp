<?php
echo '<h1>' . __('Add news') . '</h1>';

echo $this->Form->create('NewsArticle',
    array('inputDefaults' => array(
        'div' => false,
        'label' => false,
        'id' => false
    )));
echo '<h2>' . __('News heading') . '</h2>';
echo $this->Form->input('NewsArticle.heading', array('type' => 'text', 'size' => 80));

echo '<h2>' . __('News text') . '</h2>';
echo $this->Form->input('NewsArticle.text', array('type' => 'textarea', 'cols' => 80));
echo '<h2>' . __('News image') . '</h2>';
echo $this->element('mediaList', array('mediaList' => $mediaList));
echo $this->Form->end('Add');
?>