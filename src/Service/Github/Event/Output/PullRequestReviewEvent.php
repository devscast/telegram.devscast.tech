<?php

declare(strict_types=1);

namespace App\Service\Github\Event\Output;

use App\Service\OutputEventInterface;
use App\Service\Telegram\TelegramTarget;

final class PullRequestReviewEvent implements OutputEventInterface
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
            '🔥 %s %s %s pull request %s',
            $reviewer,
            $action,
            $project,
            $pullRequest,
        );
    }

    public function getTarget(): TelegramTarget
    {
        return new TelegramTarget('devscast-team');
    }
}
