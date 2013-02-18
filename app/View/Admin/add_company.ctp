<h3>Add company</h3>
<?php 
    echo $this->Form->create('Company', array('inputDefaults' => array('div' => false, 'label' => false)));
    echo $this->Form->input('Company.name', array('size' => '60', 'placeholder' => 'Write the name of the company. Be precise!'));
    echo $this->Form->end('Add');
?>