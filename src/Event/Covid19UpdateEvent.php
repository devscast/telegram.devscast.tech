<?php

declare(strict_types=1);

namespace App\Event;

/**
 * Class Covid19UpdateEvent
 * @package App\Event
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class Covid19UpdateEvent
{
    private string $update;

    /**
     * Covid19UpdateEvent constructor.
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
