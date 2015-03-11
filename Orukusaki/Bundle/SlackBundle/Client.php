<?php

namespace Orukusaki\Bundle\SlackBundle;

use Guzzle\Service\Client as BaseClient;

/**
 * Class Client
 * @package Orukusaki\Bundle\SlackBundle
 */
class Client extends BaseClient
{
    /**
     * @var array
     */
    protected $identity = [];

    /**
     * @param array $config
     *
     * @return BaseClient
     */
    public static function factory($config = array())
    {
        $client = parent::factory($config);
        $client->identity = $config['identity'];

        return $client;
    }

    /**
     * @param $args
     *
     * @return mixed
     */
    public function postMessage($args)
    {
        $args = array_merge($this->identity, $args);
        return $this->getCommand('postMessage', $args)->execute();
    }

    /**
     * @param string $keyOrPath
     * @param mixed  $value
     *
     * @return $this
     */
    public function setDefaultOption($keyOrPath, $value)
    {
        return parent::setDefaultOption($keyOrPath, $value);
    }
}
