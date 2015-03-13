<?php
namespace Orukusaki\Bundle\SlackBundle\Listener;

use DateTime;
use Orukusaki\Bundle\SlackBundle\Event\CommandReceivedEvent;
use Orukusaki\Bundle\SlackBundle\Event\MessageReceivedEvent;
use Orukusaki\Bundle\SlackBundle\Service\UserService;
use Psr\Log\LoggerInterface;

/**
 * Class LogListener
 * @package Orukusaki\Bundle\SlackBundle\Listener
 */
class LogListener
{
    /**
     * @var
     */
    protected $userService;

    /**
     * @param $userService
     * @param $logger
     */
    public function __construct(UserService $userService, LoggerInterface $logger)
    {
        $this->userService = $userService;
        $this->logger = $logger;
    }

    /**
     * @param $event
     */
    public function handleMessageEvent(MessageReceivedEvent $event)
    {
        $message = $event->getMessage();
        $date = new DateTime;

        if (isset($message['ts'])) {
            $date->setTimeStamp($message['ts']);
        }

        $username = isset($message['user_id']) ? $this->userService->getUserName($message['user_id']) : '*** me ***';

        $text = '[' . $date->format('Y-m-d H:i:s') . '] '
              . '[' . $username . '] '
              . $message['text'];

        $this->logger->info($text);
    }

    /**
     * @param $event
     */
    public function handleCommandEvent(CommandReceivedEvent $event)
    {
        $command = $event->getCommand();
        $date = new DateTime;

        $username = isset($command['user_id']) ? $this->userService->getUserName($command['user_id']) : '';

        if (isset($command['ts'])) {
            $date->setTimeStamp($command['ts']);
        }

        $text = '[' . $date->format('Y-m-d H:i:s') . '] '
            . '[' . $username . '] '
            . $command['text'];

        $this->logger->info($text);

    }
}
