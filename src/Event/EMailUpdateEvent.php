<?php

declare(strict_types=1);

namespace App\Event;

/**
 * Class EMailUpdateEvent
 * @package App\Event
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class EMailUpdateEvent implements MessageUpdateEventInterface
{
    public function __construct(private string $update)
    {
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
