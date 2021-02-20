<?php

require __DIR__ . '/../vendor/autoload.php';
(new \SourceBroker\Webp\WebpRedirectToOriginalAndConvertInBackground(
    [
        // If you change this folder you need to change also htaccess part.
        'folderInDocumentRootToSaveWebp' => '/_processed_/webp-images',
    ]
))->start();
