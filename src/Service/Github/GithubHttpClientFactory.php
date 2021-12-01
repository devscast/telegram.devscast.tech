<?php

declare(strict_types=1);

namespace App\Service\Github;

use Github\Client;
use Symfony\Component\HttpClient\HttplugClient;

class GithubHttpClientFactory
{
    public static function createInstance(): Client
    {
        return Client::createWithHttpClient(new HttplugClient());
    }
}
