<?php

declare(strict_types=1);

namespace App\Service\Github;

use App\Service\Formatter\GithubMessageFormatter;
use Github\Client;

/**
 * Class GithubService
 * @package App\Service
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class GithubService
{
    private Client $client;
    private GithubMessageFormatter $formatter;

    /**
     * GithubService constructor.
     * @param Client $client
     * @param GithubMessageFormatter $formatter
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function __construct(Client $client, GithubMessageFormatter $formatter)
    {
        $this->client = $client;
        $this->formatter = $formatter;
    }

    /**
     * @return string
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getIssues(): string
    {
        $issues = $this->client->api('issue')->all('devscast', 'devscast.tech', ['state' => 'open']);
        return $this->formatter->issues($issues, 'devscast.tech');
    }
}
