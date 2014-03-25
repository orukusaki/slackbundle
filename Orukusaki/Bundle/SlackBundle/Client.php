<?php

namespace Orukusaki\Bundle\SlackBundle;

use Guzzle\Service\Client as BaseClient;

class Client extends BaseClient
{
    protected $identity = [];

    public static function factory($config = array())
    {
        $client = parent::factory($config);
        $client->identity = $config['identity'];

        return $client;
    }

    public function postMessage($args)
    {
        $args = array_merge($this->identity, $args);

        return $this->getCommand('postMessage', $args)->execute();
    }
}
