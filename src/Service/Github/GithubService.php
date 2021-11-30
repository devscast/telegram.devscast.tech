<?php

declare(strict_types=1);

namespace App\Service\Github;

use App\Service\Formatter\GithubMessageFormatter;
use Github\Client;

/**
 * Class GithubService
 * @package App\Service
 * @author bernard-ng <bernard@devscast.tech>
 */
class GithubService
{
    public function __construct(
        private Client $client,
        private GithubMessageFormatter $formatter
    ) {
    }

    /**
     * @return string
     * @author bernard-ng <bernard@devscast.tech>
     */
    public function getIssues(): string
    {
        $issues = $this->client->api('issue')->all('devscast', 'devscast.tech', ['state' => 'open']);
        return $this->formatter->issues($issues, 'devscast.tech');
    }
}
