<?php

declare(strict_types=1);

namespace App\Event;

/**
 * Interface MessageUpdateEventInterface
 * @package App\Event
 * @author bernard-ng <bernard@devscast.tech>
 */
interface MessageUpdateEventInterface
{
    /**
     * @return string
     * @author bernard-ng <bernard@devscast.tech>
     */
    public function getUpdate(): string;
}
