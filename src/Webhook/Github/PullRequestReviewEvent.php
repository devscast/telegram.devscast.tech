<?php

declare(strict_types=1);

namespace App\Webhook\Github;

use App\Telegram\ChatId;
use App\Webhook\WebhookEventInterface;

/**
 * class PullRequestReviewEvent.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class PullRequestReviewEvent implements WebhookEventInterface
{
    public function __construct(
        protected array $data
    ) {
    }

    public function __toString(): string
    {
        $reviewer = $this->data['review']['user']['login'];
        $project = $this->data['repository']['name'];
        $pullRequest = $this->data['pull_request']['number'];
        $action = $this->data['action'];

        return sprintf(
            '⬆ %s %s %s pull request %s',
            $reviewer,
            $action,
            $project,
            $pullRequest,
        );
    }

    public function getChatId(): ChatId
    {
        return new ChatId('devscast-team');
    }

    public function getUpdate(): string|iterable
    {
        return $this->data;
    }
}
