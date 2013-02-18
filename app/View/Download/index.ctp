<?php

if (isset($file)) {
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: public");
    header("Content-Description: File Transfer");
    header("Content-Type: " . $file['mime']);
    header("Content-Disposition: attachment; filename=\"" . $file['info']['basename'] . "\"");
    header("Content-Transfer-Encoding: binary");
    header("Content-Length: " . $file['size']);

    // Start output stream by reading file segments at a time.
    $fileResource = @fopen($file['path'], 'rb');
    if ($fileResource) {
        while(!feof($fileResource)) {
            print(fread($fileResource, 1024 * 8));
            flush();
            if (connection_status() != 0) {
                fclose($fileResource);
                die();
            }
        }
        @fclose($fileResource);
    }

}
die();

?>