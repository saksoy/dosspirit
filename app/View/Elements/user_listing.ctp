<?php

echo '<ul class="cleanList">';
foreach ($users as $user) {
    if (empty($user['User']['avatar'])) {
        $user['User']['avatar'] = 'no-avatar.png';
    }

    echo '<li>
    ' . __('Level %s', array($this->CommonViewFunctions->experienceLevel($user['User']['experience']))) . '
    <a href="/profile/' . $user['User']['slug'] . '/' . $user['User']['id'] . '">' . $user['User']['username'] .'</a>
    (' . __('%s points', array($user['User']['experience']))  . ')
    </li>';
}
echo '</ul>';