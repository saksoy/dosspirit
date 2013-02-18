<?php
echo '<h1>' . __('Activate your account') . '</h1>';
echo '<p>' . __('Thanks for registering an account at The DOS Spirit!') . '</p>';
echo '<p>' . __('To activate your account, please click the link below or copy and paste the link into your browser.') . '</p>';

echo '<p><a href="' . $content . '">Activate my The DOS Spirit account!</a></p>';
echo __('If link does\'t show, copy this to your browser address field: %s', array($content));

echo '<p>' . __('If you have not registered an account at The DOS Spirit or this email is unknown to you, simply ignore this request.') . '</p>';
?>