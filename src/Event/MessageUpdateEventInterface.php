<?php

declare(strict_types=1);

namespace App\Event;

/**
 * Interface MessageUpdateEventInterface
 * @package App\Event
 * @author bernard-ng <ngandubernard@gmail.com>
 */
interface MessageUpdateEventInterface
{
    /**
     * @return string
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getUpdate(): string;
}
