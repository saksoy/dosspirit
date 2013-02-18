<?php
if (isset($previewMode)) {
    echo '<h2>You are currently previewing how this entry looks</h2>';
    echo '<div class="message">' . $publishStatus . '</div>';
    echo '<div class="message">' . $activeStatus . '</div>';
    echo '<span class="generalButton roundedCorner defaultShadow"><a href="/admin/edit/game">&lt; - Back to game listing</a></span>';
}

if (count($gameData['Serie'])) {
    $seriesArray = array();
    foreach ($gameData['Serie'] as $serie) {
        $seriesArray[] = '<a href="/' . $selectedLanguage . '/search/series/' . $serie['slug'] . '">' . $serie['name'] . '</a>';
    }

    $gameSerie = implode(', ', $seriesArray);
} else {
    $gameSerie = false;
}
?>

<h1>
<?php
echo $gameData['Game']['name'] . ' (' . $gameData['Game']['year'] . ')';
if (isset($user) && $gameData['Game']['user_id'] == $user['id']) {
    echo '<span class="generalButton roundedCorner defaultShadow editGame"><a href="/admin/edit/game/' . $gameData['Game']['slug'] . '/' . $gameData['Game']['id'] . '">Edit game</a></span>';
}
?>
</h1>

<?php
echo '<div class="subtextHeader">' .
    __('Developer') . ': <a href="/' . $selectedLanguage . '/search/company/developer/' . $gameData['Company'][0]['slug'] . '">' . $gameData['Company'][0]['name'] . '</a> - ' .
    __('Publisher') . ': <a href="/' . $selectedLanguage . '/search/company/publisher/' . $gameData['Company'][1]['slug'] . '">' . $gameData['Company'][1]['name'] . '</a> - ' .
    __('Year') . ': <a href="/' . $selectedLanguage . '/search/year/' . $gameData['Game']['year'] . '">' . $gameData['Game']['year'] . '</a>';

    if ($gameSerie !== false) {
        echo ' - ' . __('Series') . ': ' . $gameSerie;
    }

echo '</div>';
if (($gameData['Game']['dosbox_page']) > 1) {
    $gameDosbox = '<a href="http://www.dosbox.com/comp_list.php?showID=' . $gameData['Game']['dosbox_page'] . '">' . __('Dosbox compability page') . '</a>';
} elseif ($gameData['Game']['dosbox_page'] == 1) {
    $gameDosbox = '<a href="http://www.dosbox.com/comp_list.php?search=' . urlencode($gameData['Game']['name']) . '">' . __('Dosbox compability page') . '</a>';
} else {
    $gameDosbox = '';
}

if (!empty($gameData['Game']['scummvm_page'])) {
    $gameScummVM = '<a href="http://www.scummvm.org/compatibility/DEV/' . $gameData['Game']['scummvm_page'] . '">' . __('ScummVM compability page') .'</a>';
} else {
    $gameScummVM = '';
}

$categories = '<ul class="gameCategoriesList">';
foreach ($gameData['Category'] as $category) {
    $categories .= '<li><a href="/' . $selectedLanguage . '/search/category/' . $category['slug'] . '">' . $category['name_english'] . '</a></li>';
}

foreach ($gameData['Platform'] as $platform) {
    $categories .= '<li><a href="/' . $selectedLanguage . '/search/platform/' . $platform['slug'] . '">' . $platform['name'] . '</a></li>';
}
$categories .= '</ul>';

foreach ($gameData['GameMode'] as $mode) {
    $gameModes[] = ucfirst($mode['mode']);
}

$gameModes = implode(',', $gameModes);

echo $this->element('game_reputation', array(
    'gameId' => $gameData['Game']['id'],
    'gameSlug' => $gameData['Game']['slug'],
    'gameReputation' => $gameData['GameReputation']
    ));

echo '
<div class="modal hidden">
    <a href="http://www.multigamer.no"><img src="/images/multigamer-banner.png" alt="Multigamer.no" width="600" title="Multigamer, community by Altibox" /></a>
    <h1>Download ' . $gameData['Game']['name'] . '</h1>
    Proceed below to download this game.
    <p>Remember to <a href="/support">support us</a> if you enjoy what we do!</p>' .
    $this->element('google_adsense', array('type' => 'horizontal', 'height' => '90', 'width' => '800')) . '
    <p><span class="generalButton roundedCorner defaultShadow"><a href="#" id="dlLink" />Download ' . $gameData['Game']['name'] . '</a></span></p>
</div>
<div class="reviewLeftColumn">
    <div class="ingressContainer defaultShadow">';
    if ($gameData['Review']['golden'] == 1) {
        echo '<span class="goldenDosSpirit"></span>';
    }

    if (!empty($gameData['Review']['text'])) {
        $selectedClass = ' tabActive';
    } else {
        $selectedClass = '';
    }

    echo '<img src="/images/game/ingress/' . $gameData['Game']['ingress'] . '" alt="Game title image" width="100%" title="' . $gameData['Game']['name'] . ' ingress image" />';
    echo '</div>';
    echo $categories;
    echo '
    <section class="gameReview tab' . $selectedClass .'">
    <h3>' . __('Review') . '</h3>';
    if (!empty($gameData['Review']['text']) && !empty($gameData['Review']['User'])) {
        echo '<img class="textLayout" src="/images/icon/text-columns.png" title="Format text with text columns" rel="gameReviewArticle" alt="twoColumns" />
        <img class="fontFormat" src="/images/icon/font-enlarge.png" title="Change font size" rel="gameReviewArticle" alt="fontSizeLarge" />
        <article class="gameReviewArticle fontSizeNormal">
        <aside class="reviewAuthor roundedCorner defaultShadow">
		<a href="/profile/' . $gameData['Review']['User']['slug'] . '/' . $gameData['Review']['User']['id'] . '">'
		. $gameData['Review']['User']['username'] . 
        '<img src="/images/avatar/' . $gameData['Review']['User']['avatar'] . '" width="100" height="100" alt="user avatar" />
        </a>
		' . date('d.m Y', strtotime($gameData['Review']['publish_date']));

        // Add edit review button, if the review belongs to viewing user and is logged in.
        if (isset($user) && $gameData['Review']['user_id'] == $user['id']) {
            echo '<span class="generalButton roundedCorner defaultShadow editReview"><a href="/admin/edit/review/' . $gameData['Game']['slug'] . '/' . $gameData['Game']['id'] . '">Edit review</a></span>';
        }

        echo '</aside>'
        . $this->CommonViewFunctions->bbCodeString(nl2br($gameData['Review']['text'])) .
        '</article>';
    } else {
        echo __('No review available.');
        echo '<span class="generalButton roundedCorner defaultShadow addReview"><a href="/admin/add/review/' . $gameData['Game']['slug'] . '/' . $gameData['Game']['id'] .'">' . __('Add a review') . '</a></span>';
    }
    echo '</section>';

    if (empty($gameData['Review']['text'])) {
        $gameDescriptionClass = ' tabActive';
    } else {
        $gameDescriptionClass = '';
    }

    echo '<section class="gameDescription tab' . $gameDescriptionClass . '">
    <h3>' . __('Game description') . '</h3>';

    if (!empty($gameData['Game']['description'])) {
        echo nl2br($gameData['Game']['description']);
    } else {
        echo __('No game description available');
    }
    echo '</section>
    <section class="gameRapSheet tab">' .
        '<h3>' . __('Rap sheet') . '</h3>
        <ul>
            <li>' . __('Game added') . ': ' . $gameData['Game']['created'] . '</li>
            <li>' . __('Release year') . ': <a href="/search/year/' . $gameData['Game']['year'] . '">' . $gameData['Game']['year'] . '</a></li>
            <li>' . __('Developer') . ': <a href="/search/company/developer/' . $gameData['Company'][0]['slug'] . '">' . $gameData['Company'][0]['name'] . '</a></li>
            <li>' . __('Publisher') . ': <a href="/search/company/publisher/' . $gameData['Company'][1]['slug'] . '">' . $gameData['Company'][1]['name'] . '</a></li>
            <li>' . __('Game mode') . ': ' . $gameModes . '</li>
            <li>' . __('Age rating') . ': ' . $gameData['Game']['age'] . '</li>
            <li>' . __('Visits') . ': ' . $gameData['Game']['visits'] . '</li>
        </ul>

        <h3>' . __('Game entry history') . '</h3>';

        echo '<ul class="verticalNavigation">';
        foreach ($gameData['GameLog'] as $log) {
            echo '<li>' . $log['created'] . ' - ' . $log['data'] . '</li>';
        }
        echo '</ul>';

        ?>
    </section>

    <section class="gameCompability tab">
    <?php
    echo '<h3>' . __('Game compability') . '</h3>';

    if (empty($gameDosbox) && empty($gameScummVM)) {
        echo '<div class="compabilityNotice">';
        echo '<h3>' . __('No compability data found') . '</h3>';
        echo __('We have no data on how compatible this title is. Please refer to the list below for emulators on various platforms.');
        echo '<ul>';
        echo '<li>For NES games, try <a href="http://nestopia.sourceforge.net/">NEStopia</a> or <a href="http://ubernes.com/">UberNES</a>.</li>';
        echo '<li>For SNES games, try <a href="http://www.zsnes.com/">ZSnes</a>.</li>';
        echo '<li>For Sega Megadrive games, try <a href="http://www.emulator-zone.com/doc.php/genesis/gens.html">Gens</a>.</li>';
        echo '<li>For Amiga games, try <a href="http://www.winuae.net/">WinUAE</a>.</li>';
        echo '</ul>';
        echo '</div>';
    } else {
        echo '<div class="dosboxCompability">' . $gameDosbox . '</div>';
        echo '<div class="scummVMCompability">' . $gameScummVM . '</div>';
    }

    echo '</section>';

    echo '<section class="gameComments tab">' .
    $this->element('disqus',
        array('disqusCommentId' => $gameData['Game']['slug'] . '/' . $gameData['Game']['id']), array('cache' => array('config' => 'element', 'key' => 'game_' . $gameData['Game']['id'] . '_disqus'))) .
    '</section>';

    echo __('Visits: %s', array($gameData['Game']['visits'])) . '</div>';

    if ($gameData['Game']['number_of_votes'] > 0 && $gameData['Game']['votes_sum'] > 0) {
        $userScore = round($gameData['Game']['votes_sum'] / $gameData['Game']['number_of_votes'], 1);
    } else {
        $userScore = 0;
    }
?>

<div class="reviewRightColumn">
    <section class="gameScore">
        <div class="editorsScore defaultShadow roundedCorner reviewScoreBox score-<?php echo floor($gameData['Review']['total']); ?>">
            <?php
            if (isset($gameData['Review']['total'])) {
                $editorsRating = $gameData['Review']['total'];
            } else {
                $editorsRating = 'N/A';
            }
            echo __('Editor rating') .
            '<div>' . $editorsRating . '</div>
            <a id="gameScoreSummaryLink" href="#gameScoreSummary">' . __('Score summary') . '</a>';
            ?>
        </div>
        <div class="userScore defaultShadow roundedCorner reviewScoreBox score-<?php echo floor($userScore); ?>">
            <?php
            echo __('User rating') .
            '<div>' . $userScore  . '</div>' .
            __('%s votes', array($gameData['Game']['number_of_votes']));
            ?>
        </div>

        <section id="gameScoreSummary">
            <?php
            if (isset($gameData['Review']['rating'])) {
                foreach ($gameData['Review']['rating'] as $key => $rating) {
                    echo '<div class="rating"><img src="/images/icon/' . strtolower($key) . '.png" alt="icon">' . strtoupper($key) . ' : ' . $rating . '</div>';
                }
            } else {
                echo __('Game has not been reviewed yet, so no scores are available');
            }
            ?>
        </section>

        <div class="clearer"></div>
    </section>

     <?php echo $this->element('game_vote',
         array(
             'gameSlug' => $gameData['Game']['slug'],
            'gameId' => $gameData['Game']['id']
         )
     );
     ?>

    <menu class="gameTabs verticalNavigation">
        <li title="gameDescription" <?php if (empty($gameData['Review']['text'])) { echo 'class="tabSelected"'; }?>><img src="/images/icon/font.png" alt="Description icon" /><?php echo __('Description'); ?></li>
        <li title="gameReview" <?php if (!empty($gameData['Review']['text'])) { echo 'class="tabSelected"'; }?>><img src="/images/icon/pencil.png" alt="Review icon" /><?php echo __('Review'); ?></li>
        <li title="gameRapSheet"><img src="/images/icon/noteblock.png" alt="Rap sheet icon" /><?php echo __('Rap sheet'); ?></li>
        <li title="gameCompability"><img src="/images/icon/gamepad.png" alt="Gamepad/Compability icon" /><?php echo __('Compability'); ?></li>
        <li title="gameReport"><img src="/images/icon/exclamation.png" alt="Exclamation/Report icon" /><a href="/contact"><?php echo __('Report error'); ?></a></li>
        <li title="gameComments"><img src="/images/icon/user.png" alt="User/Comments icon" /><?php echo __('Comments'); ?></li>

        <?php
        if (isset($gameData['Media']['gamefile'])) {
            $downloadTitle =  __('Download %s', array($gameData['Game']['name']));
            foreach ($gameData['Media']['gamefile'] as $gameFile) {
                echo '<li title="' . $downloadTitle . ' (' . $gameFile['comment'] . ')"><img src="/images/icon/save.png" alt="Download icon"/><a href="/download/' . $gameData['Game']['slug'] . '/' . $gameData['Game']['id'] . '/' . $gameFile['id'] . '" id="downloadGame">' . $downloadTitle . '</a></li>';
            }
        }
        ?>
    </menu>

    <section class="media">
    <?php
    // Avoid game files to show up in screenshot gallery, they can't be shown as images.
    echo $this->element('game_media_list',
        array('media' => $gameData['Media']),
        array('cache' => array('config' => 'element', 'key' => 'game_' . $gameData['Game']['id'] . '_medialist'))
    );
    ?>
    </section>
</div>

<div class="clearer"></div>
<script type="text/javascript">
$(function() {
    $('section#gameScoreSummary').hide();

    $('a#downloadGame').bind('click', function(e) {
        e.preventDefault();
        $('#dlLink').attr('href', $(this).attr('href'));
        $('.modal').show();
        $('.modal').bind('click', function() {
            $('.modal').hide();
        });
    });

    $('a#gameScoreSummaryLink').bind('click', function(e) {
        e.preventDefault();
        $('section#gameScoreSummary').fadeToggle();
    });

    $('img.textLayout').bind('click', function() {
        switch ($(this).attr('alt')) {
        case 'twoColumns':
            $('article.' + $(this).attr('rel')).addClass('twoColumns');
            $(this).attr('alt', 'none');
            $(this).attr('src', '/images/icon/text.png');
            $(this).attr('title', 'Remove text columns');
            break;
        case 'none':
            $('article.' + $(this).attr('rel')).removeClass('twoColumns');
            $(this).attr('src', '/images/icon/text-columns.png');
            $(this).attr('alt', 'twoColumns');
            $(this).attr('title', 'Format with text columns');
            break;
        }
    });

    $('img.fontFormat').bind('click', function() {
        $('article.' + $(this).attr('rel')).removeClass('fontSizeNormal, fontSizeLarge');
        $('article.' + $(this).attr('rel')).addClass($(this).attr('alt'));
        switch ($(this).attr('alt')) {
            case 'fontSizeNormal':
                $(this).attr('src', '/images/icon/font-enlarge.png');
                $(this).attr('alt', 'fontSizeLarge');
                break;

            case 'fontSizeLarge':
                $(this).attr('src', '/images/icon/font.png');
                $(this).attr('alt', 'fontSizeNormal');
                break;
        }
    });

    $('menu.gameTabs li').bind('click', function(e) {
        $('li.tabSelected').removeClass('tabSelected');
        $(this).addClass('tabSelected');

        $('section.tabActive').removeClass('tabActive');
        var tabToShow = $(this).attr('title');
        $('section.'+tabToShow).addClass('tabActive');
    });
});
</script>