# sotm2014-php
This is a simple, mostly static HTML, version of the SOTM 2014 (Buenos Aires) conference website. It's ready to be deployed to http://2014.stateofthemap.org to bring that site back online.

Currently you can only see it running here: http://harrywood.dev.openstreetmap.org/sotm2014-php/

## Static HTML?
It's a set of php files, although it's almost pure static HTML. The various pages are just doing a simple php include of `header.php` and `footer.php`. Other than that there are no moving parts ...except...

## wiki mirror
`session.php` is including `wiki-mirror.inc.php`.  This is a limited caching proxy, limited only to proxying content from the OpenStreetMap wiki for the session pages of the site (the tabular overview list of sessions, and individual session pages with descriptions)

wiki-mirror.inc.php can optionally write proxied content to a file cache e.g. set `$CACHEDIR = '/tmp/cache/'` at the top of the wiki-mirror.inc.php file. Leave it as `$CACHEDIR = false` and it will not try to write any files.

Disabling cache files may be preferred for security peace-of-mind, although the caching mechanism doesn't present any obvious security loopholes (let me know if you see a problem!)  XSS attacks via wiki edits are also not an obvious issue, since we take output from mediawiki's syntax sanitization.

## Original site?
The original site is ruby on rails. Available on github here: https://github.com/osm-ar/libreconf/tree/early-bird but kind of a pain in the ass to get working. It has a database for no particular reason ...well the reason is that there was a grand idea to make a database-driven generalised conference website system. This is all overkill for a simple site, particularly where it's just documenting a past event.

This repo is also a bit of redesign of the original site, trimming down the content to be more clearly documenting a past event. I've tried to keep some of the 'venue etc' stuff for historical interest, but sidelined it a bit. The original site had several pages of info like this which is no longer needed. It did have translation into spanish for those bits of content, but...  well we can re-introduce translation here if somebody really wants it, but there's not actually much content left apart from the session descriptions (non of which were translated anyway)

The original site also had a very similar wiki mirroring mechanism implemented by me as a rails controller! [wiki_mirror_controller.rb](https://github.com/osm-ar/libreconf/blob/early-bird/app/controllers/wiki_mirror_controller.rb)



