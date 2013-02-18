<?php
echo '<h1>' . __('Activate my account') . '</h1>';
echo '<p>' . __('Paste your activation code below from the account creation email. Or, simply follow the link in that email.') . '</p>';
echo $this->Session->flash();

echo $this->Form->create('User',
array(
    'url' => '/admin/activate',
    'inputDefaults' => array(
    	'div' => false, 'label' => false
    )
));
echo $this->Form->input('User.email', array('label' => 'Email', 'value' => $userEmail));
echo $this->Form->input('UserActivationCode.key', array('label' => 'Activation code'));
echo $this->Form->end('Activate account!');

?>
