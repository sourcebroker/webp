<?php

namespace SourceBroker\Webp;

class WebpRedirectToOriginalAndConvertInBackground
{
    /**
     * @var array
     */
    private $options = [
        'folderInDocumentRootToSaveWebp' => '/_processed_/webp-images',
        'cwebp-try-supplied-binary-for-os' => false,
        'lockFolderMode' => 0777,
    ];

    public function __construct($userOptions = [])
    {
        $this->options = array_merge($this->options, $userOptions);
    }

    public function start()
    {
        $documentRoot = rtrim($_SERVER['DOCUMENT_ROOT'], '/');
        $requestUriNoQueryString = explode('?', $_SERVER['REQUEST_URI'])[0];
        $sourceFile = realpath($documentRoot . urldecode($requestUriNoQueryString));
        if (
            strpos($sourceFile, $documentRoot) === 0
            && file_exists($sourceFile)
            && in_array(pathinfo($sourceFile, PATHINFO_EXTENSION), ['png', 'jpeg', 'jpg'])
        ) {
            ob_start();
            header("Location: ?processing", true, 307);
            ob_end_flush();
            flush();
            fastcgi_finish_request();

            $destinationRootFolder = $documentRoot . $this->options['folderInDocumentRootToSaveWebp'];
            $destinationFile = $destinationRootFolder . substr($sourceFile, strlen($documentRoot));

            $lockDir = $destinationFile . '.lock';
            $destinationFileWebp = $destinationFile . '.webp';

            if (mkdir($lockDir, $this->options['lockFolderMode'], true) && is_dir($lockDir)) {
                register_shutdown_function(static function () use ($sourceFile, $destinationFileWebp, $lockDir) {
                    \WebPConvert\WebPConvert::convert($sourceFile, $destinationFileWebp, []);
                    rmdir($lockDir);
                });
            }
        }
    }
}
