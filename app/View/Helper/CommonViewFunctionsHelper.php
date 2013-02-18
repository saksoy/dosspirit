<?php

//App::uses('AppHelper', 'View/Helper');
class CommonViewFunctionsHelper extends AppHelper {

    /**
     * Helps format bb code tags.
     * @param string $string
     */
    public function bbcodeString($string) {
        $pattern = array('/\[b\](.*?)\[\/b\]/is',
                       '/\[i\](.*?)\[\/i\]/is',
                       '/\[u\](.*?)\[\/u\]/is',
                       '/\[url="(.*?)"\](.+?)\[\/url\]/is',
                       '/\[quote\](.*?)\[\/quote\]/is',
                       '/\[color=(.*?)\](.*?)\[\/color\]/is',
                       '/\[url\](.*?)\[\/url\]/is',
                       '/\[img\](.*?)\[\/img\]/is',
                       '/\[img="(.*?)"\](.+?)\[\/img\]/is',
                       '/\[img\](.+?)\[\/img\]/is',
                       '/\[yt\](.+?)\[\/yt\]/is',
                       '/\[yt="(.*?)\](.+?)\[\/yt\]/is',
                       '/\[emo\](.+?)\[\/emo\]/is',
                       '/\[size=(.*?)\](.*?)\[\/size\]/is',
                       '/\[code\](.*?)\[\/code\]/is'
                       );

                       $replace = array('<strong>$1</strong>', // bold
                      '<em>$1</em>', // italics
                      '<u>$1</u>', // underlined
                      '<a href="$1" target="_blank">$2</a>', // Link
                      '<blockquote class="quote">$1</blockquote>', // quote
                      '<span style="color: $1;">$2</span>',
                      '<a href="$1" target="_blank">Link &raquo;</a>',
                      '<img src="$1" alt="image" border="0" />',
        			  '<div class="imageHeader">$2</div><img src="$1" alt="image" border="0" />',
        			  '<img src="$1" alt="image" border="0" />',
                      '<div class="video">
                      <embed flashvars=""
                      pluginspage="http://www.adobe.com/go/getflashplayer"
                      src="http://www.youtube.com/v/$1&amp;hl=en_US&amp;fs=1"
                      type="application/x-shockwave-flash" version="9" height="400" width="600">
					  </div>',
                      '<div class="video">
                      <div class="videoHeader">$2</div>
                      <embed flashvars=""
                      pluginspage="http://www.adobe.com/go/getflashplayer"
                      src="http://www.youtube.com/v/$1&amp;hl=en_US&amp;fs=1"
                      type="application/x-shockwave-flash" version="9" height="400" width="600">
					  </div>',
                      '<img src="/images/icon/emoticon/$1.png" alt="emoticon $1" title="$1 emotion" />',
                      '<span style="font-size:$1px;">$2</span>',
                      '<code>$1</code>'
                      );

                      $text = preg_replace($pattern, $replace, $string);
                      return $text;
    }

    public function experienceLevel ($xp) {
        return floor(log(($xp + 120) / 100) * 6);
    }

    function experienceToNextLevel ($xp) {
        return floor((exp(($this->experienceLevel($xp) + 1) / 6)) * 100 - 120 - $xp);
    }

    /**
     * Substrings a string with whole words.
     * @param string $string
     * @param int $numberOfWords
     */
    public function substringWholeWords($string, $numberOfWords) {
        $content = explode(' ', $string);
        $intstr = array_slice($content, 0, $numberOfWords);
        $newString = implode(' ', $intstr);
        return $newString;
    }

    public function memberDuration($joinDate) {
        $joinDate = strtotime($joinDate);
        $diff = ceil(((int) time() - (int) $joinDate) / 86400);

        $modulu = $diff % 365;

        if ($modulu == $diff) {
            $years = 0;
            $days = $diff;
        } else {
            $years = ($diff - ($modulu)) / 365;
            $days = $modulu;
        }

        if ($years == 0) {
            return __('Been a member for %s days', array($days));
        } else {
            return __('Been a member for %s years and %s days', array($years, $days));
        }

    }

}