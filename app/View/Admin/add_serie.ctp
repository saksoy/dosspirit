<h1>Add a new serie</h1>
<?php 
    echo $this->Form->create('Serie', array(
        'inputDefaults' => array(
            'div' => false, 
            'label' => false)
    ));

    echo $this->Form->input('Serie.name', array('size' => '80', 'placeholder' => 'Write the serie name. Example: King\'s Quest or Super Mario.'));
    echo $this->Form->end('Add');
?>