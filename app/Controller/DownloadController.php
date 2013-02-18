<?php

class DownloadController extends AppController {
    public function index($gameSlug, $gameId, $mediaId) {
        // Define the domain downloads should be available for.
        $domain = 'dosspirit.net';
        set_time_limit(0);
        // Infer a restriction on filesizes for non-registered members in megabytes.
        $filesizeLimit = '10';

        $this->loadModel('Game');
        $this->loadModel('Media');
        $gameEntry = $this->Game->checkIfGameExists($gameId, $gameSlug);
        $errors = array();
        $fileFound = false;

        // Avoid hotlinking.
        if (stripos(strtolower($this->request->referer()), $domain) === false) {
            $fileFound = false;
            // Just redirect to the game page if hotlinking is detected.
            if (!isset($selectedLanguage) || empty($selectedLanguage)) {
                $selectedLanguage = 'eng';
            }
            
            $this->Session->setFlash(__('Hotlinking to files is not allowed. For sharing links, please use the link to this page.'));
            $this->redirect('/' . $selectedLanguage . '/game/' . $gameSlug . '/' . $gameId);
        } else {
            if ($gameEntry) {
                // Retrieve a media file by game id and media id. This yields max one and one only result.
                $mediaEntry = $this->Game->Media->getMediaFile($mediaId, $gameId);

                // There is exactly one hit
                if ($mediaEntry) {
                    // Handle external urls by redirecting users there.

                    // Set +1 to download count.
                    $this->Media->id = $mediaEntry['Media']['id'];
                    $this->Media->game_id = $mediaEntry['Media']['game_id'];
                    $this->Media->saveField('visits', ($mediaEntry['Media']['visits'] + 1));

                    if (substr(strtolower($mediaEntry['Media']['filename']), 0, 7 ) == 'http://') {
                        $this->redirect($mediaEntry['Media']['filename']);
                    }

                    $filePath = WWW_ROOT . 'gamefiles' . DS . $mediaEntry['Media']['filename'];

                    if (file_exists($filePath)) {
                        $filesizeInMegaBytes = filesize($filePath)/ 1024 / 1024;
                        if ($this->Auth->user('id') || ($filesizeInMegaBytes < 10 && !$this->Auth->user('id'))) {
                                $pathInfo = pathinfo($filePath);
                                $fileArray = array(
                                    'mime' => $this->CommonFunctions->getMimeType($filePath),
                                    'info' => $pathInfo,
                                    'size' => filesize($filePath),
                                    'path' => $filePath
                                );

                                $fileFound = true;
                                $this->set('file', $fileArray);
                        } else {
                            $errors[] = __('Files beyond 10Mb requires login. You can login or register an account here %s.', array('<a href="/login">Login / Register</a>'));
                            $fileFound = false;
                        }
                    } else {
                        $errors[] = __('We could not find anything on that URI. You should probably double check it.');
                        $fileFound = false;
                    }
                }
            } else {
                $errors[] = __('Invalid game entry.');
            }
        }
        if (!$fileFound) {
            $this->layout = 'main';
            $this->viewPath = 'Errors';
            $this->set('errorHeading', __('Download error'));
            $this->set('errors', $errors);
            $this->render('general_error');
        }
    }
}