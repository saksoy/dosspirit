<?php
echo '<h1 id="userInbox">Inbox</h1>';
?>
<em>Sort inbox by</em>
<menu class="mainNavigation horizontalNavigation">
<?php
echo '<li>' . $this->Paginator->sort('created', 'Date', array('direction' => 'desc')) . '</li>';
echo '<li>' . $this->Paginator->sort('type', 'Message type') . '</li>';
echo '<li>' . $this->Paginator->sort('reward', 'Experience points') . '</li>';
?>
</menu>
<?php
echo $this->element('inbox', array('inbox' => $userInbox));
echo $this->element('default_pagination_markup');
?>