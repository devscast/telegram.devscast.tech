<?php

declare(strict_types=1);

namespace App\Service\Formatter;

/**
 * Class Covid19MessageFormatter
 * @package App\Service\Formatter
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class Covid19MessageFormatter
{
    /**
     * @param array $data
     * @return string
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function format(array $data): string
    {
        $date = date('d M Y H:i');
        return <<< MESSAGE
Voici les dernières actualités sur le covid19 en RDC \n
🤒 Cas Confirmés : **{$data['confirmed']}**
✨ Guérisons : **{$data['recovered']}**
😓 Morts : **{$data['deaths']}**

**{$date}**
MESSAGE;
    }
}
