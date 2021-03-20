<?php

declare(strict_types=1);

namespace App\Service\Github;

use App\Event\Github\GithubIssueUpdateEvent;
use App\Event\Github\Webhook\IssuesEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PlayLoadService
 * @package App\Service\Github
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class PlayLoadService
{
    private EventDispatcherInterface $dispatcher;

    /**
     * PlayLoadService constructor.
     * @param EventDispatcherInterface $dispatcher
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Trigger the right event with the right data
     * @param Request $request
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function negociate(Request $request): void
    {
        $event = $request->headers->get('X-GitHub-Event');
        $guid = $request->headers->get('X-GitHub-Delivery');
        $data = json_decode($request->getContent(), true);

        switch ($event) {
            case 'issues':
                $this->dispatcher->dispatch(new IssuesEvent("issues", $guid, $data));
        }
    }
}
