<?php
namespace Orukusaki\Bundle\SlackBundle\Service;

/**
 * Class UserService
 * @package Orukusaki\Bundle\SlackBundle\Service
 */
class UserService
{
    /**
     * @var
     */
    protected $client;

    /**
     * @var array
     */
    protected $users = array();

    /**
     * @param $client
     */
    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * @param $userId
     *
     * @return mixed
     */
    public function getUserName($userId)
    {
        if ($user = $this->getUser($userId)) {
            return $user['name'];
        }
    }

    /**
     * @param $userId
     *
     * @return mixed
     */
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
