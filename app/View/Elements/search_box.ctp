<div class="search">
    <form method="post" action="/search/term/">
        <input type="text" name="term" size="17" id="query" placeholder="<?php echo __('Search for something'); ?>" />
    </form>
</div>

<script type="text/javascript">
$(function() {
    $('#query').autocomplete({
        serviceUrl:'/search/term/',
        minChars: 2,
        deferRequestBy: 200,
        maxHeight: 800,
        width: 500,
    });

    $('#query').bind('focus', function() {
        $(this).attr('size', '30');
    });

    $('#query').bind('blur', function() {
        $(this).attr('size', '17');
    });
});

</script>