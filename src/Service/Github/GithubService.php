<?php

declare(strict_types=1);

namespace App\Service\Github;

use Github\Api\Issue;
use Github\Client;

final class GithubService
{
    public function __construct(
        private Client                 $client,
        private GithubMessageFormatter $formatter
    ) {
    }

    public function getIssues(string $username = 'devscast', string $repository = 'devscast.tech'): string
    {
        /** @var Issue $api */
        $api = $this->client->api('issue');
        $issues = $api->all($username, $repository, ['state' => 'open']);
        return $this->formatter->issues($issues, $repository);
    }
}
