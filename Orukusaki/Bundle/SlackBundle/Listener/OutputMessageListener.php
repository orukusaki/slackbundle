<?php
namespace Orukusaki\Bundle\SlackBundle\Listener;

use DateTime;

class OutputMessageListener
{
    protected $userService;

    public function __construct($userService)
    {
        $this->userService = $userService;
    }

    public function handleMessageEvent($event)
    {
        $message = $event->getMessage();
        $date = new DateTime;
        $date->setTimeStamp($message['ts']);
        $username = isset($message['user']) ? $this->userService->getUserName($message['user']) : '*** me ***';

        echo '[' . $date->format('Y-m-d H:i:s') . '] ';
        echo '[' . $username . '] ';
        echo $message['text'] . PHP_EOL;
    }
}
