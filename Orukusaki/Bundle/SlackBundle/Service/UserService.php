<?php
namespace Orukusaki\Bundle\SlackBundle\Service;

class UserService
{
    protected $client;
    protected $users = array();

    public function __construct($client)
    {
        $this->client = $client;
    }

    public function getUserName($userId)
    {
        if ($user = $this->getUser($userId)) {
            return $user['name'];
        }
    }

    public function getUser($userId)
    {
        if (!array_key_exists($userId, $this->users)) {
            $this->fetchUsers();
        }

        if (array_key_exists($userId, $this->users)) {
            return $this->users[$userId];
        }
    }

    private function fetchUsers()
    {
        $response = $this->client->getUserList();
        $members = $response['members'];

        foreach ($members as $member) {
            $this->users[$member['id']] = $member;
        }
    }
}
