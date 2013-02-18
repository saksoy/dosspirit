<?php
echo '<h1>' . __('Forgot password') . '</h1>';
echo __('Someone, hopefully you, have requested a password reset. Follow the link below if this is correct, otherwise ignore this request.');
$content = explode("\n", $content);

foreach ($content as $line) {
    echo '<p> ' . $line . '</p>' . "\n";
}
?>