<?php

declare(strict_types=1);

namespace App\Event;

/**
 * Class Covid19UpdateEvent
 * @package App\Event
 * @author bernard-ng <bernard@devscast.tech>
 */
class Covid19UpdateEvent implements MessageUpdateEventInterface
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
