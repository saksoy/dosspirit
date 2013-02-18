<?php

class ImageController extends AppController {

    var $uses = array('Screenshot', 'UserScreenshot');

    public function index() {
    }

    public function getAll() {
        $gameId = $this->passedArgs;
        $gameId = $gameId[0];

        // Enable access only internally
        if (isset($this->params['requested'])) {
            $cond = array(
			'conditions' => array('refid' => $gameId, "deleted = 0 AND bilde != ''"),
			'fields' => array('id','refuserid', 'bilde','kommentar', 'vist'),
            'group' => array('bilde'),
			'order' => array('id ASC')
            );
            return $this->Screenshot->find('all', $cond);
        } else {
            $this->redirect('/game/review/' . $gameId);
        }
    }

    /**
     * Finds all user submitted images also known as gallery
     */
    public function gallery() {
        $gameId = $this->passedArgs;
        $gameId = $gameId[0];
        $cond = array(
			'conditions' => array('refID' => $gameId, "godkjent = 1"),
			'fields' => array('id','bilde', 'refID','brukerID', 'dato', 'bildekommentar'),
            'group' => array('bilde'),
			'order' => array('id ASC')
        );

        return $this->Userscreenshot->find('all', $cond);
    }

    public function overlay() {

    }

    public function view() {
        $this->autoRender = false;
        ini_set('memory_limit', '48M');
        $params = $this->passedArgs;

        $file = $params[0];

        // Fallback for missing size parameter.
        if (isset($params[1])) {
            $size = $params[1];
        } else {
            $size = 320;
        }

        // Whether to cache
        if (isset($params[3]) && ($params[3] == 'cache')) {
            $caching = true;
        } else {
            $caching = false;
        }

        // Whether to apply watermark
        if (isset($params[2]) && $params[2] == 'watermark') {
            $applyWatermark = 1;
        } else {
            $applyWatermark = 0;
        }


        if (isset($params['type'])) {
            switch ($params['type']) {
                case 'focus':
                    $imagePathPart = 'game' . DS . 'focus';
                    break;

                case 'ingress':
                    $imagePathPart = 'game' . DS . 'ingress';
                    break;

                case 'news':
                    $imagePathPart = 'news';
                    break;

                case 'pool':
                    $imagePathPart = 'mediapool';
                    break;

                case 'cover':
                    $imagePathPart = 'game' . DS . 'cover';
                    break;

                case 'avatar':
                    $imagePathPart = 'avatar';
                    break;

                default:
                    $imagePathPart = 'game';
                    break;
            }
        } else {
            $imagePathPart = 'game';
        }

        /**
         * Thumbnail creator with support for caching
         * @author: bakkelun
         * @copyright The DOS Spirit, www.dosspirit.net
         */


        $imageTypes = array(
            'jpg' => 'image/jpeg',
        	'jpeg' => 'image/jpeg',
        	'gif' => 'image/gif',
        	'png' => 'image/png',
        );

        // Config
        $cachePath = CACHE . 'images';
        $quality = 80;
        $imageName = IMAGES . $imagePathPart . DS . $file;
        $watermarkName = IMAGES . 'watermark.png';
        $fileCacheTime = date('D, d M Y H:i:s', strtotime('+1 month')) . ' GMT';
        $newWidth = intval($size);

        // Default width to 320 if erronous.
        if ($newWidth <= 0) {
            $newWidth = 320;
        }

        if ($newWidth < 2000) {
                // If image not found, revert to the default one and scale + cache it appropriately.
                if (!file_exists($imageName)) {
                    $imageName = IMAGES . 'image-not-found.png';
                }

                // Find out some info about the image, check if it's a valid one
                $imageFormat = end(explode('.', $imageName));
                $imageFormat = strtolower($imageFormat);

                if (key_exists($imageFormat, $imageTypes)) {
                    if ($imageFormat == 'jpeg'){
                        $imageFormat = 'jpg';
                    }

                    // Obtain source image dimensions
                    list($imageWidth, $imageHeight, $imageType) = getimagesize($imageName);
                    $resizeFactor = $imageWidth / $newWidth;
                    $newHeight = ceil($imageHeight/$resizeFactor);
                    $imageNameCached = str_replace('/','', md5($file . $newWidth . $imageWidth . $imageHeight . $quality) . '-' . $newWidth . '-cached.' . strtolower($imageFormat));

                    if ($caching && file_exists(CACHE . 'images' . DS . $imageNameCached)) {
                        header('Content-type: ' . $imageTypes[$imageFormat]);
                        header('Expires: ' . $fileCacheTime);
                        header('Content-Disposition: inline; filename=' . $imageNameCached);
                        echo (join('', file($cachePath . DS . $imageNameCached)));
                        //$this->response->send();
                        die(); # no need to create image - it already exists in the cache
                    }

                    // Create a blank image
                    $target = imagecreatetruecolor($newWidth, $newHeight);

                    switch($imageFormat) {
                        case 'gif':
                            $image = imagecreatefromgif($imageName);
                            break;

                        case 'jpg':
                            $image = imagecreatefromjpeg($imageName);
                            break;

                        case 'png':
                            $image = imagecreatefrompng($imageName);
                            break;
                    }

                    // Create a watermark image.
                    $watermarkImage = imagecreatefrompng($watermarkName);

                    list($watermarkWidth, $watermarkHeight, $watermarkType) = getimagesize($watermarkName);

                    // Copy resized image into blank image
                    ImageCopyResampled($target, $image, 0, 0, 0, 0, $newWidth, $newHeight, $imageWidth, $imageHeight);

                    if ($applyWatermark) {
                        // Copy the watermark image into the resized image if desired.
                        ImageCopyMerge($target, $watermarkImage, $newWidth-$watermarkWidth, $newHeight-$watermarkHeight, 0, 0, $watermarkWidth, $watermarkHeight, 100);
                        imageDestroy($watermarkImage);
                    }

                    // Clean up in-memory objects
                    imageDestroy($image);

                    header('Content-type: ' . $imageTypes[$imageFormat]);

                    switch($imageFormat) {
                        case 'gif':
                            if ($caching && is_dir($cachePath)) {
                                imagegif($target, $cachePath . DS . $imageNameCached, $quality);
                            }
                            imagegif($target);
                            break;

                        case 'jpg':
                            if ($caching && is_dir($cachePath)) {
                                imagejpeg($target, $cachePath . DS . $imageNameCached, $quality);
                            }
                            imagejpeg($target);
                            break;

                        case 'png':
                            if ($caching && is_dir($cachePath)) {
                                imagepng($target, $cachePath . DS . $imageNameCached);
                            }
                            imagepng($target);
                            break;

                            // No valid file extension, exit.
                        default:
                            die();
                    }

                    die();
                }

        }

    }
}
?>