<?php

declare(strict_types=1);

namespace App\Service\Bitcoin;

final class BitcoinMessageFormatter
{
    public function format(array $data): string
    {
        return <<< MESSAGE
Voici le cours du Bitcoin maintenant : \n
💰 1 BTC : {$data['bpi']['USD']['rate']} USD
💰 1 BTC : {$data['bpi']['EUR']['rate']} EUR
💰 1 BTC : {$data['bpi']['GBP']['rate']} GBP

{$data['time']['updated']}
MESSAGE;
    }
}
