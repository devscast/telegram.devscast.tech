<?php

declare(strict_types=1);

namespace App\Service\Covid19;

final class Covid19MessageFormatter
{
    public function format(array $data): string
    {
        $date = date('d M Y H:i');
        return <<< MESSAGE
Voici les dernières actualités sur le covid19 en RDC \n
🤒 Cas Confirmés : {$data['confirmed']}
✨ Guérisons : {$data['recovered']}
😓 Morts : {$data['deaths']}

{$date}
MESSAGE;
    }
}
