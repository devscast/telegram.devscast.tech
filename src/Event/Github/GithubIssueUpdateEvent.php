<?php

declare(strict_types=1);

namespace App\Event\Github;

use App\Event\MessageUpdateEventInterface;

/**
 * Class GithubIssueUpdateEvent
 * @package App\Event\Github
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class GithubIssueUpdateEvent implements MessageUpdateEventInterface
{
    private string $update;

    /**
     * GithubIssueUpdateEvent constructor.
     * @param string $update
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function __construct(string $update)
    {
        $this->update = $update;
    }

    /**
     * @return string
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getUpdate(): string
    {
        return $this->update;
    }
}
