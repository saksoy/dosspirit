<div class="googlead <?php echo $type; ?>">
    <?php
    $adIds = array(
    	'vertical' => '8573136945',
    	'horizontal' => '5199928597'
    );
    ?>

    <script type="text/javascript">
    <!--
    google_ad_client = "ca-pub-9629542049878762";
    google_ad_slot = "<?php echo $adIds[$type]; ?>";
    google_ad_width = <?php echo $width; ?>;
    google_ad_height = <?php echo $height; ?>;
    //-->
    </script>
    <script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
</div>