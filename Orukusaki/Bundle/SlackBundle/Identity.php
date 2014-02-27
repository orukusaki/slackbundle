<?php
namespace Orukusaki\Bundle\SlackBundle;

class Identity
{
    public $username;
    public $emoji;

    public function __construct($username, $emoji)
    {
        $this->username = $username;
        $this->emoji = $emoji;
    }
}
