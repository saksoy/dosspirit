<?php
echo '<h1>' . __('Password changed successfully') . '</h1>';
echo '<p>' . __('Your password has been updated. You can now login to The DOS Spirit using your new password and continue contributing!');
echo '<br /><a href="http://' . $_SERVER['SERVER_NAME'] . '/admin/login">' . __('You can login by following this link') . '</a></p>';
$content = explode("\n", $content);

foreach ($content as $line) {
    echo '<p> ' . $line . '</p>' ."\n";
}
?>