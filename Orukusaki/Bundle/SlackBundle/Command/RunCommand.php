<?php

namespace Orukusaki\Bundle\SlackBundle\Command;

use Orukusaki\Bundle\SlackBundle\Event\MessageReceivedEvent;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunCommand extends Command
{
    protected $latest;
    protected $client;
    protected $dispatcher;

    public function __construct($client, $dispatcher)
    {
        parent::__construct();

        $this->client = $client;
        $this->dispatcher = $dispatcher;
        $this->latest = time();
    }

    protected function configure()
    {
        $this
            ->setName('slack:run')
            ->setDescription('Monitor a Group')
            ->addArgument('group', InputArgument::REQUIRED, 'group name');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $response = $this->client->getGroupList();

        foreach ($response['groups'] as $group) {
            if ($group['name'] == $input->getArgument('group')) {
                $groupId = $group['id'];
                break;
            }
        }
        if (!isset($groupId)) {
            throw new \RuntimeException('Could not identify group');
        }

        $output->writeln('Listening to group: ' . $input->getArgument('group'));

        while (true) {

            sleep(1);

            $messages = $this->getLatestMessages($groupId);

            krsort($messages);

            foreach ($messages as $message) {
                if ($message['type'] == 'message' && isset($message['text'])) {
                    $event = new MessageReceivedEvent($message, $groupId);
                    $this->dispatcher->dispatch(MessageReceivedEvent::KEY, $event);
                }
            }

        }
    }

    private function getLatestMessages($groupId)
    {
        $response = $this
            ->client
            ->getGroupHistory(array('channel' => $groupId, 'oldest' => $this->latest));

        if (!empty($response['messages'])) {

            $this->latest = $response['messages'][0]['ts'];

            return $response['messages'];
        }

        return array();

    }
}
