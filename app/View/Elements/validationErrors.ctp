<?php

echo '<h3>' . __('There are errors you need to look into!') . '</h3>';

echo '<ul>';
foreach ($validationErrors as $index => $key) {


    echo __('Error for field %s', array($index));
    if (is_array($key)) {
        foreach ($key as $message) {
            echo '<li>' . $message . '</li>';
        }
    }

}

echo '</ul>';
?>