Kwik is a wiki engine written in PHP, MediaWiki compatible, but without the bloated features.

It's intended to be used as a personal or small organization wiki, where users are trusted and time is not meant to be spent on maintenance.

## Key features ##
  * Quick setup.
  * Up to 100 times faster than MediaWiki.
  * Better text search than MediaWiki.
  * Skip preconfiguration process: just unzip and run.
  * MediaWiki syntax compatible.
  * Filesystem as storage, there's no need for a database backend.
  * Friendly URLs.

## Requirements ##
  * Apache2 webserver with mod\_rewrite enabled. Lighttpd support has dropped, for now (sorry!).
  * PHP 5 or greater, either as module or as cgi.
  * Access to cd, ls, rm and grep system commands for search feature. Windows servers must provide them via cygwin.

## Installation ##
Unzip the files on the root folder of your running webserver. At least, the _pages_ directory has to be writeable by the webserver user.

Point your browser to http://localhost/ and enter default credentials user _user_ and password _password_.

If you want, edit the list of allowed users in index.php and/or add versioning to pages directory with your preferred SCM.