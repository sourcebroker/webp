<?php

require __DIR__ . '/../vendor/autoload.php';

(new WebpRedirectToOriginalAndConvertInBackground(
    [
        'folderInDocumentRootToSaveWebp' => '/__processed__/webp-images',
    ]
))->start();