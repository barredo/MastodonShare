MastodonShare
=============

MastodonShare is a small PHP based web application that provides a simple
"share to Mastodon" endpoint.  Visitors can enter their Mastodon instance
and are redirected to that instance's sharing page with the provided text
and URL.  The project powers `mastodonshare.com`.

Features
--------
* Presents a form to users so they can select their Mastodon instance.
* Remembers the chosen instance in a cookie to avoid asking again.
* Accepts `text`, `url` and optional `note` parameters to prefill the share.
* Redirects users straight to the share URL on their instance.
* Includes simple example pages and Bootstrap based styling.

This repository contains the PHP sources and vendor libraries required for
the service.  There are no build steps or tests.  Deploy the code on a
standard PHP server and configure `m/config.php` for your environment.
