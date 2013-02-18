<?php echo $this->Html->docType(); ?>
<head>
<?php
echo $this->Html->charset('utf-8');
?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="robots" content="index, follow">

<title><?php echo $title_for_layout ?> - The DOS Spirit</title>
<meta name="description" content="The DOS Spirit er Norges eneste seri&oslash;se nettsted som fokuserer p&aring; de gode gamle dagene i spillindustrien for DOS-spill vi enten kjenner til eller ei, hater og/eller elsker." />
<meta name="rating" content="General" />
<meta name="Author" content="The DOS Spirit Foundation" />
<meta name="copyright" content="Copyright &copy; dosspirit.net and The DOS Spirit Foundation(&copy;) 2004-<?php echo date('Y', time()); ?>" />
<meta name="distribution" content="global" />

</head>
<body>
<div id="page">
    <img src="http://www.dosspirit.net/images/logo.png" alt="logo" title="The DOS Spirit" />
    <div class="content">
        <?php echo $this->fetch('content'); ?>
    </div>

    <?php echo $this->element('footer'); ?>
</div>

</body>
</html>