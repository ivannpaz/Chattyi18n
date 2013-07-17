<?php

namespace App;

use App\Controllers\HomeController;
use Buzz\Client\Curl;
use Igorw\Silex\ConfigServiceProvider;
use MatthiasNoback\Silex\Provider\MicrosoftTranslatorServiceProvider;
use Predis\Silex\PredisServiceProvider;
use Silex\Application\TwigTrait;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TwigServiceProvider;

/**
 *
 */
class Application extends \Silex\Application
{

    use TwigTrait;

    /**
     * Build the application.
     *
     * @param array $values
     */
    public function __construct(array $values = array())
    {
        parent::__construct($values);

        $this->registerServices();
        $this->registerRoutes();
    }

    /**
     * Register required services
     */
    protected function registerServices()
    {
        $this->register(new ConfigServiceProvider(__DIR__ . "/config/config.yml"));

        $this->register(new ServiceControllerServiceProvider());

        $this->register(new TwigServiceProvider(), [
            'twig.path' => __DIR__.'/views',
        ]);

        $this->register(new SessionServiceProvider());

        $this->register(new PredisServiceProvider(), [
            'predis.parameters' => 'tcp://127.0.0.1:6379',
        ]);

        $this->register(new MicrosoftTranslatorServiceProvider(), array(
            'microsoft_oauth.client_id' => $this['msoauth']['client_id'],
            'microsoft_oauth.client_secret' => $this['msoauth']['client_secret'],
        ));
    }

    /**
     * Register application routes
     */
    protected function registerRoutes()
    {
        $this['home.controller'] = $this->share(function() {
            return new HomeController($this);
        });

        $this->get('/chat', "home.controller:chat");
        $this->get('/clear', "home.controller:clear");
        $this->get('/', "home.controller:index");
    }
}
