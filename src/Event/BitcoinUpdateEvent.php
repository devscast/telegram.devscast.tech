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
    private string $update;

    /**
     * BitcoinUpdateEvent constructor.
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
