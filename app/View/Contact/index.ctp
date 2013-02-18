<?php
echo '<h1>' . __('Contact us') . '</h1>';

if (isset($feedbackSent) && $feedbackSent) {
    echo '<div class="message ok">' . __('Thank you. The goblins will look at it shortly.') . '</div>';
} else {
    echo '<p>' . __('Like what we do? Something amiss or gone horribly wrong? Whatever the case, get in touch below.') . '</p>';

    if (isset($error)) {
        echo '<div class="message error">' . $error . '</div>';
    }
    echo $this->Form->create('Contact', array(
	'inputDefaults' => array(
		'div' => false,
		'label' => false)
    )
    );

    echo '<h3>' . __('Your name') . '</h3>';
    echo $this->Form->input('name', array('size' => '65', 'autofocus' => true, 'placeholder' => 'Name is optional.'));
    echo '<h3>' . __('Your email, so we can contact you') . ' *</h3>';
    echo $this->Form->input('email', array('size' => '65', 'placeholder' => 'Email must be valid.'));
    echo '<h3>' . __('Feedback') . ' *</h3>';
    echo $this->Form->textarea('feedback', array('cols' => '65', 'rows' => '10', 'maxlength' => 300, 'placeholder' => 'Describe the nature of your feedback, report etc. Include game name if it\'s regarding a game.'));
    echo '<p><em>' . __('* fields are required.') . '</em></p>';
    echo $this->Form->end('Send');
}
?>