<h1><?php echo $errorHeading; ?></h1>
<?php 
if (isset($errors) && is_array($errors)) {
?>
<ul class="error">
<?php 
    
    foreach ($errors as $error) {
        echo '<li>' . $error . '</li>';
    }
?>
</ul>
<?php 
}
?>

<p><?php echo __('If you feel this error is not your fault, feel free to %s contact us here %s.', array('<a href="/contact">', '</a>')); ?></p>