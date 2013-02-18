<h1>Password Help!</h1>
<?php
if (!isset($resetStarted)) {
echo 'Holy goblin, my password has gone missing! Relax, no worries, input the email you used to register your account and we\'ll send you instructions on how to reset your password.';
echo $this->Form->create('User', array('url' => array('controller' => 'admin', 'action' => 'reset')));
echo $this->Form->input('email');
echo $this->Form->end('Send reset instructions');
} else {
    echo __('Password reset instructions have been sent to the provided email address. Check your inbox!');
}

?>