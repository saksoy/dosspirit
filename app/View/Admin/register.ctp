<?php
echo '<h1>' . __('Register account') . '</h1>';
echo $this->Session->flash();

echo $this->Form->create('User');
echo $this->Form->input('User.username');
echo $this->Form->input('User.password');
echo $this->Form->input('User.name');
echo $this->Form->input('User.location');
echo $this->Form->input('User.email');
echo $this->Form->input('security_challenge', array('label' => 'Please enter the sum of ' . $mathCaptcha));

echo $this->Form->end('Register');
?>