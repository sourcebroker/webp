<?php

namespace SourceBroker\Webp;

class WebpRedirectToOriginalAndConvertInBackground
{
    /**
     * @var array
     */
    private $options = [
        'folderInDocumentRootToSaveWebp' => '/_processed_/webp-images',
        'lockFolderMode' => 0777,
        'webPConvertOptions' => [
            'cwebp-try-supplied-binary-for-os' => false
        ],
    ];

    public function __construct($userOptions = [])
    {
        $this->options = array_merge($this->options, $userOptions);
    }

    public function start()
    {
        $documentRoot = realpath(rtrim($_SERVER['DOCUMENT_ROOT'], '/'));
        $requestUriNoQueryString = explode('?', $_SERVER['REQUEST_URI'])[0];
        $sourceFileFromRealpath = realpath($documentRoot . urldecode($requestUriNoQueryString));
        if (is_callable($this->options['sourceFileFromRealpathCallable'])) {
            $sourceFileFromRealpathRelative = call_user_func($this->options['sourceFileFromRealpathCallable'], $sourceFileFromRealpath, $documentRoot);
        } else {
            $sourceFileFromRealpathRelative = str_replace($documentRoot, '', $sourceFileFromRealpath);
        }

        if (
            $sourceFileFromRealpathRelative === $requestUriNoQueryString
            && file_exists($sourceFileFromRealpath)
            && in_array(strtolower(pathinfo($sourceFileFromRealpath, PATHINFO_EXTENSION)), ['png', 'jpeg', 'jpg'])
        ) {
            ob_start();
            header("Location: ?processing", true, 307);
            ob_end_flush();
            flush();
            if (function_exists('fastcgi_finish_request')) {
                fastcgi_finish_request();
            }

            $destinationRootFolder = $documentRoot . $this->options['folderInDocumentRootToSaveWebp'];
            $destinationFile = $destinationRootFolder . $sourceFileFromRealpathRelative;

            $lockDir = $destinationFile . '.lock';
            $destinationFileWebp = $destinationFile . '.webp';

            if (mkdir($lockDir, $this->options['lockFolderMode'], true) && is_dir($lockDir)) {
                $webPConvertOptions = $this->options['webPConvertOptions'];
                register_shutdown_function(static function () use ($sourceFileFromRealpath, $destinationFileWebp, $lockDir, $webPConvertOptions) {
                    \WebPConvert\WebPConvert::convert($sourceFileFromRealpath, $destinationFileWebp, $webPConvertOptions);
                    rmdir($lockDir);
                });
            }
        }
    }
}