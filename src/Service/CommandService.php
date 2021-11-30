<?php

declare(strict_types=1);

namespace App\Service;

use App\Service\Formatter\CommandMessageFormatter;

/**
 * Class CommandService
 * @package App\Service
 * @author bernard-ng <bernard@devscast.tech>
 */
class CommandService
{
    public function __construct(
        private CommandMessageFormatter $formatter
    ) {
    }

    public const COMMANDS = [
        // general commands
        '/start' => '[public] affiche les commandes disponibles',
        '/covid19' => '[public] affiche les dernières actualités du covid19 en RDC',
        '/bitcoin' => '[public] affiche le cours actuel du bitcoin',

        // devscast.tech related commands
        '/devscast_mails' => '[private] affiche les mails non lu de contact@devscast.tech',
        '/devscast_booking' => '[private] affiche les dernières demande de mentoring sur devscast.tech',
        '/devscast_clear' => '[private] supprime le cache sur le serveur devscast.tech',
        '/devscast_logs' => '[private] envoie la dernière version du fichier log du jour',
        '/devscast_backup' => '[private] lance un backup SQL sur le serveur devscast.tech',
        '/devscast_archive' => '[private] crée une archive du projet avec un hash unique sur le serveur devscast.tech',
        '/devscast_version' => '[private] renvoie la référence du dernier commit du serveur devscast.tech',
        '/devscast_rsync' => '[private] synchronise la version github à la version production sur devscast.tech'
    ];

    public function start(): string
    {
        return $this->formatter->start(self::COMMANDS);
    }
}
