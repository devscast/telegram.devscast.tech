<?php

declare(strict_types=1);

namespace App\Service\Formatter;

/**
 * Class BitcoinMessageFormatter
 * @package App\Service\Formatter
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class BitcoinMessageFormatter
{
    /**
     * @param array $data
     * @return string
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function format(array $data): string
    {
        return <<< MESSAGE
Voici le cours du Bitcoin maintenant : \n
💰 1 BTC : **{$data['bpi']['USD']['rate']} USD**
💰 1 BTC : **{$data['bpi']['EUR']['rate']} EUR**
💰 1 BTC : **{$data['bpi']['GBP']['rate']} GBP**

https://coinze.tech
**{$data['time']['updated']}**
MESSAGE;
    }
}
