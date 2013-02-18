<?php

/**
 * Common functions component
 * @author bakkelun
 *
 */
class CommonFunctionsComponent extends Component {

    /**
     * Removes special characters from input. Used for almost everything that needs to be sanitized.
     * @param string $input
     * @return string
     */
    public function cleanString($input) {
        $input = stripslashes(trim(strip_tags($input)));
        return strtolower(preg_replace('/[^a-zA-Z0-9s]+/i', '-', $input));
    }

    public function intoUrlString($input) {
        $input = stripslashes(trim(strip_tags($input)));
        return strtolower(preg_replace('/[^a-zA-Z0-9]/i', '_', $input));
    }

    /**
     * Retrieves the file extension (if any) in $input.
     * @param string $input
     * @return boolean
     */
    public function getFileExtension($input) {
        return strtolower(end(explode('.', $input)));
    }

    /**
     * Based upon file name extension, retrieves the appropriate mime type.
     * @param string $fileName
     * @return string
     */
    public function getMimetype($fileName) {
        $extension = $this->getFileExtension($fileName);

        $validExtensions = array (
        // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar',
        // documents
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
        // images
            'gif' => 'image/gif',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
        // audio
            'mp3' => 'audio/mpeg',
            'wav' => 'audio/x-wav'
            );

            if (array_key_exists($extension, $validExtensions)) {
                return (string) $validExtensions[$extension];
            } else {
                return 'application/octet-stream';
            }
    }

    /**
     * Checks if the provided extension is a image format we support.
     * @param string $extension
     * @return boolean|null
     */
    public function checkValidImageExtension($fileName) {
        $extension = $this->getFileExtension($fileName);
        //$extension = strtolower($extension);
        if ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png' || $extension == 'gif') {
            return true;
        } else{
            return;
        }
    }

    /**
     * Checks if the provided extension is a media format we support.
     * @param string $extension
     * @return boolean|null
     */
    /*public function checkValidMediaExtension($fileName) {
     $extension = $this->getFileExtension($fileName);

     $a = array(
     'jpg',
     'jpeg',
     'png',
     'gif',
     'nes',

     );
     //$extension = strtolower($extension);
     if ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png' || $extension == 'gif') {
     return true;
     } else{
     return;
     }
     }*/

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

    /**
     *
     * Uploads a file to the provided destination.
     * @param array $fileEntry
     * @param string $destinationPath
     * @param string $fileName
     */
    public function uploadFile($fileEntry, $destinationPath, $fileName) {
        if (!move_uploaded_file($fileEntry['tmp_name'], $destinationPath . DS . $fileName)) {
            return;
        } else {
            return true;
        }
    }

    public function uploadIngressAndFocusImage($data, $gameId) {
        // Upload ingress image.
        $baseFileName = $gameId . '-' . $this->cleanString($data['Game']['name']);
        $fileReferences = array();
        $errors = array();

        $ingressImage = $data['IngressImage'];
        if ($ingressImage['error'] == 0) {
            if ($this->checkValidImageExtension($ingressImage['name'])) {
                $ingressFilename = $baseFileName . '.' . $this->getFileExtension($ingressImage['name']);
                if (!move_uploaded_file($ingressImage['tmp_name'], IMAGES . 'game' . DS . 'ingress' . DS . $ingressFilename)) {
                    $errors[] = 'Could not upload ingress image. Fault?';
                } else {
                    $fileReferences['ingress'] = $ingressFilename;
                }
            } else {
                $errors[] = __('Ingress image has invalid image format: "%s"', array($ingressImage['name']));
            }
        }

        // Upload focus image.
        $focusImage = $data['FocusImage'];

        if ($focusImage['error'] == 0) {
            if ($this->checkValidImageExtension($focusImage['name'])) {
                $focusFilename = $baseFileName . '.' . $this->getFileExtension($focusImage['name']);
                if (!move_uploaded_file($focusImage['tmp_name'], IMAGES . 'game' . DS . 'focus' . DS . $focusFilename)) {
                    $errors[] = __('Could not upload focus image.');
                } else {
                    $fileReferences['focus'] = $focusFilename;
                }
            } else {
                $errors[] = __('Focus image has invalid image format: "%s"', array($focusImage['name']));
            }
        }

        if (count($errors) > 0) {
            return $errors;
        } else {
            return $fileReferences;
        }
    }

    public function uploadImage($data, $type, $gameId) {
        $baseFileName = $gameId . '-' . $this->cleanString($data['Game']['name']);
        $fileReference = '';
        $errors = array();

        $image = $data;

        if ($this->checkValidImageExtension($image['name'])) {
            $filename = $baseFileName . '.' . $this->getFileExtension($image['name']);
            if (!move_uploaded_file($image['tmp_name'], IMAGES . 'game' . DS . $type . DS . $filename)) {
                $errors[] = 'Could not upload ingress image. Fault?';
            } else {
                $fileReference = $filename;
            }
        } else {
            $errors[] = __('Image has invalid image format: "%s"', array($image['name']));
        }

        if (count($errors) > 0) {
            return $errors;
        } else {
            return $fileReference;
        }
    }
}