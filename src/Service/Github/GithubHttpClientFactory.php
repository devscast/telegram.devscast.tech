<?php

declare(strict_types=1);

namespace App\Service\Github;

use Github\Client;
use Symfony\Component\HttpClient\HttplugClient;

/**
 * Class GithubHttpClientFactory
 * @package App\Service\Github
 * @author bernard-ng <bernard@devscast.tech>
 */
class GithubHttpClientFactory
{
    /**
     * @return Client
     * @author bernard-ng <bernard@devscast.tech>
     */
    public static function createInstance(): Client
    {
        return Client::createWithHttpClient(new HttplugClient());
    }
}
