<?php
echo '<h1>' . __('Reset password') . '</h1>';
echo $this->Session->flash();
if (isset($info) && isset($info['email']) && isset($info['rk'])) {
    echo $this->Form->create('User');
    echo $this->Form->input('User.password', array('label' => __('New password')));
    echo $this->Form->input('User.repeatPassword', array('type' => 'password', 'label' => __('Repeat password')));
    echo $this->Form->input('User.email', array('type' => 'hidden', 'value' => $info['email']));
    echo $this->Form->input('User.resetkey', array('type' => 'hidden', 'value' => $info['rk']));
    echo $this->Form->end(__('Change password'));
}

?>