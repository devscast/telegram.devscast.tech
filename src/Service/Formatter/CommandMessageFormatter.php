<?php

declare(strict_types=1);

namespace App\Service\Formatter;

/**
 * Class CommandMessageFormatter
 * @package App\Service\Formatter
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class CommandMessageFormatter
{
    public function start(array $commands = []): string
    {
        $msg = "List de command disponible \n\n";
        foreach ($commands as $command => $description) {
            $msg .= "🔧 $command : $description \n\n";
        }
        return $msg;
    }
}
