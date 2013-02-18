<h1>Login to The DOS Spirit</h1>
<div class="message">Existing users: Please "Reset password" as your old ones won't work anymore!</div>
<?php
    echo $this->Session->flash('auth');
    echo $this->Form->create('User', array('url' => '/admin/login'));
    echo $this->Form->input('email');
    echo $this->Form->input('password');
    echo $this->Form->end('Login');

    echo '<span class="generalButton roundedCorner defaultShadow">' . $this->Html->link('Register new account', '/admin/register') . '</span>';
    echo '<span class="generalButton roundedCorner defaultShadow">' . $this->Html->link('Forgot your password?', '/admin/reset') . '</span>';
?>