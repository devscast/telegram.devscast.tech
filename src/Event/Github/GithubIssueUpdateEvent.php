<?php

declare(strict_types=1);

namespace App\Event\Github;

use App\Event\MessageUpdateEventInterface;

/**
 * Class GithubIssueUpdateEvent
 * @package App\Event\Github
 * @author bernard-ng <bernard@devscast.tech>
 */
class GithubIssueUpdateEvent implements MessageUpdateEventInterface
{
    public function __construct(private string $update)
    {
    }

    /**
     * @return string
     * @author bernard-ng <bernard@devscast.tech>
     */
    public function getUpdate(): string
    {
        return $this->update;
    }
}
