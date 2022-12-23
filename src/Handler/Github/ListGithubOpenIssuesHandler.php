<?php

declare(strict_types=1);

namespace App\Handler\Github;

use App\Command\OpenIssuesCommand;
use App\Event\Github\OpenIssuesEvent;
use Github\Api\Issue;
use Github\Client;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * class GithubService.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class OpenIssuesHandler
{
    public function __construct(
        private readonly Client $client,
        private readonly EventDispatcherInterface $dispatcher
    ) {
    }

    public function __invoke(OpenIssuesCommand $command): void
    {
        $update = $this->getIssues();
        $this->dispatcher->dispatch(new OpenIssuesEvent($update));
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
