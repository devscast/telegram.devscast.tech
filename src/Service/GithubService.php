<?php

declare(strict_types=1);

namespace App\Service;

use Github\Client;

/**
 * Class GithubService
 * @package App\Service
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class GithubService
{
    private Client $client;

    /**
     * GithubService constructor.
     * @param Client $client
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return string
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getIssues(): string
    {
        $issues = $this->client->api('issue')->all('devscast', 'devscast.tech', ['state' => 'open']);
        $data = [];
        foreach ($issues as $issue) {
            $title = "#{$issue['number']} {$issue['title']}";
            $assignee = $issue['assignee'] ? $issue['assignee']['login'] : 'bernard-ng';
            $data[] = <<< MESSAGE

🛠 **$title**
🚻 **$assignee**

MESSAGE;
        }

        $message = join(' ', $data);
        return <<< MESSAGE
Salut les gars, j'espère que vous allez bien, alors il y a encore du travail 
sur le projet devscast.tech, voici un petit rappel et les tâches de chacun
fermez l'issue sur github pour signaler que vous avez fini

$message

Prochain rappel demain à 12h
MESSAGE;
    }
}
