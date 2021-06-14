<?php

declare(strict_types=1);

namespace App\Event;

/**
 * Class BitcoinUpdateEvent
 * @package App\Event
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class BitcoinUpdateEvent implements MessageUpdateEventInterface
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
