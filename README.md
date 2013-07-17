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
burn your cat, dog or parrot if caught on sight.

Usage:
------

- Clone
- Rename `site/app/config/config.yml.dist` to `config.yml` and fill in your MSOauth credentials.
- make the `site/public` folder available on apache
- Fire Up the server `cd site && php app/Server.php`

TODO:
-----

- Create private sessions (right now is quite promiscuous!) (That's what redis was included in this project, but still unused!)


Resources/Contents:
-------------------

- Vagrant + Virtualbox base boxes with LAMP stack
- Everything glued together via [Silex](https://github.com/fabpot/Silex).
- View rendering: [Twig](https://github.com/fabpot/Twig)
- Redis support: [Predis](https://github.com/nrk/predis) + [Predis Service Provider](https://github.com/nrk/PredisServiceProvider)
- Configuration: [ConfigProvider](https://github.com/igorw/ConfigServiceProvider)
- Translation: [Microsoft Translator v2 Api](https://github.com/matthiasnoback/microsoft-translator) + [Service Provider](https://github.com/matthiasnoback/MicrosoftTranslatorServiceProvider)
- CSS Framework: [Furatto](http://icalialabs.github.io/furatto/)
