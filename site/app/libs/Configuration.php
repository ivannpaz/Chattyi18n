<?php

namespace App\Libs;

/**
 *
 */
class Configuration
{

    protected $config = array(

    );

    public function get($key, $default = false)
    {
        return isset($this->config[$key]) ? $this->config[$key] : $default;
    }
}
