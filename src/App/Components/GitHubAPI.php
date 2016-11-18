<?php
namespace App\Components;

/**
 * @author Alexander Kononenko <contact@hauntd.me>
 * @package App\Github
 */
class GitHubAPI
{
    private $client;

    public function __construct()
    {
        $this->client = new \Github\Client();
    }

    /**
     * @param $username
     * @return mixed
     */
    public function getRepositoriesFor($username)
    {
        return $this->client->api('user')->repositories($username, 'owner', 'updated', 'desc');
    }
}
