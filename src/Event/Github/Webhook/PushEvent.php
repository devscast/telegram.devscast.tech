<?php

declare(strict_types=1);

namespace App\Event\Github\Webhook;

/**
 * Class PushEvent
 * @package App\Event\Github\Webhook
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class PushEvent implements GithubWebhookEventInterface
{
    private string $name;
    private string $guid;
    private array $playLoad;

    /**
     * IssuesEvent constructor.
     * @param string $name
     * @param string $guid
     * @param array $playLoad
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function __construct(string $name, string $guid, array $playLoad)
    {
        $this->name = $name;
        $this->guid = $guid;
        $this->playLoad = $playLoad;
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
