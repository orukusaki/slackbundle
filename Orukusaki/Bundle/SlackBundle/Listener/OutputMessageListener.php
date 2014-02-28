<?php
namespace Orukusaki\Bundle\SlackBundle\Listener;

use DateTime;

class OutputMessageListener
{
    protected $userService;

    public function __construct($userService, $logger)
    {
        $this->userService = $userService;
        $this->logger = $logger;
    }

    public function handleMessageEvent($event)
    {
        $message = $event->getMessage();
        $date = new DateTime;

        if (isset($message['ts'])) {
            $date->setTimeStamp($message['ts']);
        }

        $username = isset($message['user']) ? $this->userService->getUserName($message['user']) : '*** me ***';

        $text = '[' . $date->format('Y-m-d H:i:s') . '] '
              . '[' . $username . '] '
              . $message['text'] . PHP_EOL;

        $this->logger->info($text);
    }
}
