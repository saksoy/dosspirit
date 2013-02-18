<?php
echo '<h1>' . __("%s's profile", array($this->data['User']['username'])) . ' (Level: ' . $this->CommonViewFunctions->experienceLevel($this->data['User']['experience']) . ')</h1>';
echo '<div class="contentColumn">';
echo '<ul class="cleanList">';
//echo '<li>' . __('Username') . ': ' . $this->data['User']['username'] . '</li>';
echo '<li>' .__('Account created') . ': ' . $this->data['User']['created'] . '</li>';
echo '<li>' .__('Account activated') . ': ' . $this->data['User']['activated'] . '</li>';
echo '<li>' .__('Email') . ': ' . $this->data['User']['email'] . '</li>';
echo '<li>' .__('Experience points') . ': ' . $this->data['User']['experience'] . '</li>';
//echo '<li>' .__('Account level') . ': ' . $this->CommonViewFunctions->experienceLevel($this->data['User']['experience']) .'</li>';
echo '<li>' .__('Experience needed to next level') . ': ' .$this->CommonViewFunctions->experienceToNextLevel($this->data['User']['experience']) .'</li>';
echo '</ul>';

echo '<h1>' . __('Achievements') . '</h1>';
echo '<ul class="cleanList" id="profileAchievements">';
echo '<li>list</li>';
echo '</ul>';
echo '</div>';

echo '<div class="contentColumn">';
echo $this->Form->create('User', array('type' => 'file', 'inputDefaults' => array('div' => false, 'label' => false)));
echo '<h3>' . __('Location') . '</h3>';
echo $this->Form->input('User.location');
echo '<h3>' . __('Name') . '</h3>';
echo $this->Form->input('User.name');
if (!empty($this->data['User']['avatar'])) {
    echo '<h3>' . __('Current avatar') . '</h3>';
    echo '<p><img src="/images/avatar/' . $this->data['User']['avatar'] . '" width="100" alt="avatar" /></p>';
}

echo '<h4>' . __('Upload avatar image') . '</h4>';
echo $this->Form->file('avatar', array('accept' => 'image/*', 'name' => 'data[avatar]'));
echo $this->Form->end('Update');
echo '</div>';