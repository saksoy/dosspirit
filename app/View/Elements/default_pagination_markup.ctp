<?php
    echo '<section class="paginator">';
    echo '<p>' . $this->Paginator->numbers() . '</p>';
    echo $this->Paginator->prev(__('Previous'), null, null, array('class' => 'disabled'));
    echo $this->Paginator->next(__('Next'), null, null, array('class' => 'disabled'));
    echo $this->Paginator->counter(
    '<h3>' . __('Page %s of %s. Showing %s out of total %s. Starting at %s and ending on %s.',
    array('{:page}', '{:pages}', '{:current}', '{:count}', '{:start}', '{:end}')) . '</h3>');
    echo '</section>';
?>