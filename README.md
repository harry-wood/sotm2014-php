# sotm2014-php
This is a PHP version of the SOTM 2014 (Buenos Aires) conference website. It's no longer needed as the http://2014.stateofthemap.org was brought back online there via a static site generating solution Firefishy came up with.

You can see this old PHP version running here: http://harrywood.dev.openstreetmap.org/sotm2014-php-original/

## Static bits
There's a set of php files which are almost pure static HTML, just doing a simple php include of `header.php` and `footer.php`. Other than that there are no moving parts, except...

## wiki mirror
Here's the interesting bit, and I still like this idea as a way of allowing wiki-editability while presenting content in a glossy stylised website like this.

`session.php` is including `wiki-mirror.inc.php`.  This is a limited caching proxy, limited only to proxying content from the OpenStreetMap wiki for the session pages of the site (the tabular overview list of sessions, and individual session pages with descriptions)

wiki-mirror.inc.php can writes proxied content to a file cache e.g. set `$CACHEDIR = '/tmp/cache/'` at the top of the wiki-mirror.inc.php file. Leave it as `$CACHEDIR = false` and it will not try to write any files. The caching mechanism doesn't present any obvious security loopholes (let me know if you see a problem!) XSS attacks via wiki edits are also not an obvious issue, since we scrape HTML output from mediawiki after it has applied its syntax sanitization.

## The original SOTM 2014 rails app
The _original_ site for SOTM 2014 was a ruby on rails app. Available on github here: https://github.com/osm-ar/libreconf/tree/early-bird but kind of a pain to get working. It has a database for no particular reason ...well the reason is that there was a grand idea to make a database-driven generalised conference website system. This is all overkill for a simple site, particularly where it's just documenting a past event.

The original rails app site also had a very similar wiki mirroring mechanism implemented by me as a rails controller! [wiki_mirror_controller.rb](https://github.com/osm-ar/libreconf/blob/early-bird/app/controllers/wiki_mirror_controller.rb)

This PHP repo is also a bit of redesign of that original site, trimming down the content to be more clearly documenting a past event. I've tried to keep some of the 'venue etc' stuff for historical interest, but sidelined it a bit. The original site had several pages of info like this which is no longer needed. It did have translation into Spanish for those bits of content, but...  well I planned to re-introduce a translation here if anybody really wanted it, event though there's not actually much content left apart from the session descriptions (non of which were translated anyway)



