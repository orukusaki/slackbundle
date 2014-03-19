<?php

namespace Orukusaki\Bundle\SlackBundle;

use Guzzle\Common\Collection;
use Guzzle\Service\Client as BaseClient;

class Client extends BaseClient {

    protected $identity = [];

    public static function factory($config = array())
    {
        $client = parent::factory($config);
        $client->identity = $config['identity'];
    }

    public function postMessage($args)
    {
        $args = array_merge($client->identity, $args);
        return $this->getCommand('postMessage', $args)->execute();
    }
} 
