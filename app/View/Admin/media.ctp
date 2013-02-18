<h1>Administrate uploaded media</h1>

<?php
echo $this->Form->create('MediaPool');
echo $this->element('mediaList', array('mediaList' => $mediaList));

echo $this->Form->end('Approve selected media');
?>

<p><em>Approved media will be available for anyone that adds data to The DOS Spirit</em></p>