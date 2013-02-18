<?php
/**
 *
 * https://github.com/tim-kos/CakePHP-Zip-Helper
 * Modified to be used as a component for controllers. Fixed an issue with files being packed as directories which was
 * unneccessary.
 *
 */
class ZipComponent extends Component {
    public function create($destination = '', $files = array(), $overwrite = false) {
        if (file_exists($destination) && !$overwrite) {
            return false;
        }

        $validFiles = array();
        if (is_array($files)) {
            foreach ($files as $file) {
                if (file_exists($file)) {
                    $validFiles[] = $file;
                } else {
                    echo 'file:' . $file . 'not exists.!?';
                }
            }
        }


        if (count($validFiles) < 1) {
            return false;
        }

        // Append the dosspirit readme text file to any zip action.
        $validFiles[] = WWW_ROOT . 'gamefiles' . DS . 'dosspirit.net.README.txt';

        $zip = new ZipArchive();

        $type = $overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE;
        if ($zip->open($destination, $type) !== true) {
            return false;
        }

        $dest = str_replace('.zip', '', basename($destination));

        foreach ($validFiles as $file) {
            $zip->addFile($file, basename($file));
        }
        $zip->close();

        return file_exists($destination);
    }
}