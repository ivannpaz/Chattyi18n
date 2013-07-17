Chattyi18n
==========

###Proof of concept and ultra-basic implementation

Description:
------------

Chat application with realtime translations via BING's API. (Google decided to charge for theirs).

Notes:
------

- App Quality: ALPHA STAGE
- Caching: No caching atm, it will blow away your Translation API monthly rate if not careful!
- App Security: Crap. As a matter of fact, don't use this as is! It may
burn your cat, dog or parrot if seen on sight.

Usage:
------

- Clone
- Rename `site/app/config/config.yml.dist` to `config.yml` and fill in your MSOauth credentials.
- make the `site/public` folder available on apache
- Fire Up the server `cd site && php app/Server.php`

TODO:
-----

- Create private sessions (right now is quite promiscuous!)
