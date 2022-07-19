<?php

declare(strict_types=1);

namespace App\Service\Bitcoin\Event\Input;

use App\Service\InputEventInterface;

final class BitcoinEvent implements InputEventInterface
{
    public function __construct(private readonly array $update)
    {
    }

    public function __toString(): string
    {
        return <<< MESSAGE
Voici le cours du Bitcoin maintenant : \n
💰 1 BTC : {$this->update['bpi']['USD']['rate']} USD
💰 1 BTC : {$this->update['bpi']['EUR']['rate']} EUR
💰 1 BTC : {$this->update['bpi']['GBP']['rate']} GBP

{$this->update['time']['updated']}
MESSAGE;
    }

    public function getUpdate(): array
    {
        return $this->update;
    }
}
