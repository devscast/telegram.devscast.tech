<?php

declare(strict_types=1);

namespace App\Service\Github;

use Github\Api\Issue;
use Github\Client;

/**
 * class GithubService.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class GithubService
{
    public function __construct(
        private readonly Client $client,
    ) {
    }

    public function getIssues(string $username = 'devscast', string $repository = 'devscast.tech'): array
    {
        /** @var Issue $api */
        $api = $this->client->api('issue');
        return $api->all($username, $repository, params: [
            'state' => 'open',
        ]);
    }
}
