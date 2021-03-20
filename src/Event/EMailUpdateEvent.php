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
    private string $update;

    /**
     * EMailUpdateEvent constructor.
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
