<?php

require __DIR__ . '/../vendor/autoload.php';
(new \SourceBroker\Webp\WebpRedirectToOriginalAndConvertInBackground(
    [
        'folderInDocumentRootToSaveWebp' => '/typo3temp/assets/_processed_/webp-images',
    ]
))->start();

