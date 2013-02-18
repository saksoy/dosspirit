<?php 

class RadioController extends AppController {
    
    public function index() {
        $this->layout = 'radio';
        
        $songCount = count(glob(WWW_ROOT . 'music' . DS . '*.mp3'));
        
        $this->set('songCount', $songCount);
    }
    
    public function playlist() {
        // TODO: Update this
        header("Content-type: text/xml");
        $this->autoRender = false;
        $folder = WWW_ROOT . 'music';

        define( 'CRLF', "\r\n" );

        $d = dir( $folder );

        $f = array();
        
        //-- get files
        while ( false !== ( $file = $d->read()))
            if (strtolower(substr($file, -3, 3)) == 'mp3')
            $f[$file] = strtolower($file);
        //-- sort
        array_multisort($f, SORT_STRING, SORT_ASC);
        reset($f);
        
        //-- output xml
        echo '<playlist version="1.1">'.CRLF.'<title>The DOS Spirit Retroplayer Playlist</title>'.CRLF.'<info>www.dosspirit.net</info>'.CRLF.'
        
        <trackList>'.CRLF;
        foreach ($f as $file => $val) {
            $fileTitle = explode('.', $file);
            echo chr( 9 ).'
            <track>'.CRLF.'<annotation>'.ucwords(str_replace("-","",strtolower($fileTitle[0]))).'</annotation>' . CRLF . '<location>/music/' . $file . '</location>'.CRLF.'</track>
            ';
        }
        echo '
        </trackList>
        </playlist>
        ';
        $d->close();
    }
}