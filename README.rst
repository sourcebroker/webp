webp
====

.. contents:: :local:

What does it do?
----------------

This library is short scenarios of working examples for webp on demand which use rosell-dk/webp-convert in background.
There will be two scenarios:

1) first call to image which has no webp version yet will redirect to original source and run webp image converter in background
2) first call to image which has no webp version yet will stream original source and run webp image converter in background

Next calls to jpg/png will return webp on apache rewrite level.

For mow only first scenario is implemented.

Webp images generated after redirect to original source
-------------------------------------------------------

How to install ?
++++++++++++++++

Go to folder ``Resources/WebpRedirectToOriginalAndConvertInBackground``

1. Copy part of htaccess from there to your htaccess or vhost configuration.
2. Copy the example ``webp-on-demand.php`` to your DocumentRoot folder. Change the path to vendor folder to your needs.
3. Your webp files will be stored in separate folder in your DocumentRoot folder. By default its ``__processed__/webp-images``.
   You can change this folder by replacing ``__processed__/webp-images`` in ``.htaccess`` file and in ``webp-on-demand.php`` file.

How to test?
++++++++++++

1. Go into Network tab in Chrome DevTools. When you refresh website you should see jpg/png files being redirected to the
   same url but with ``?processing`` parameter.
2. When you refresh the page second time some jpg/png should be already converted. Those will have ``webp`` in column ``Type``
3. Check you DocumentRoot folder there should be ``__processed__/webp-images`` folder created with webp files.
