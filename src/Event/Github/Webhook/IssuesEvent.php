<?php

declare(strict_types=1);

namespace App\Event\Github\Webhook;

/**
 * Class IssuesEvent
 * @package App\Event\Github\Webhook
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class IssuesEvent implements GithubWebhookEventInterface
{
    public function __construct(
        private string $guid,
        private array $playLoad,
        private string $name = "issues"
    ) {
    }

    /**
     * @return string
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getGuid(): string
    {
        return $this->guid;
    }

    /**
     * @return string
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getPlayLoad(): array
    {
        return $this->playLoad;
    }
}
