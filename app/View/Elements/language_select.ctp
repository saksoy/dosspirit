<li class="subMenuListItem" id="languageSelect"><a href="#"><?php echo __('Choose language'); ?></a>
    <ul class="subMenu" id="languageList">
        <li><?php echo $this->Html->link('English', array('language' => 'eng') + $this->params['pass']); ?></li>
        <li><?php echo $this->Html->link('Norsk', array('language' => 'no-nb') + $this->params['pass']); ?></li>
        <li><?php echo $this->Html->link('Nynorsk', array('language' => 'no-nn') + $this->params['pass']); ?></li>
        <li><?php echo $this->Html->link('Espaniol', array('language' => 'spa') + $this->params['pass']); ?></li>
        <li><?php echo $this->Html->link('Deutsch', array('language' => 'deu') + $this->params['pass']); ?></li>
        <li><?php echo $this->Html->link('Italiano', array('language' => 'ita') + $this->params['pass']); ?></li>
        <li><?php echo $this->Html->link('Pirate English', array('language' => 'eng-pt') + $this->params['pass']); ?></li>
    </ul>
</li>