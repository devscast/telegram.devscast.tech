<?php

declare(strict_types=1);

namespace App\Service\Telegram\Subscriber;

use App\Service\Devscast\FeedReaderService;
use App\Service\Telegram\Event\CommandEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Exception;
use TelegramBot\Api\InvalidArgumentException;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

/**
 * class CommandSubscriber.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class CommandSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly BotApi $api,
        private readonly LoggerInterface $logger,
        private readonly FeedReaderService $feedReaderService,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CommandEvent::class => 'onCommand',
        ];
    }

    public function onCommand(CommandEvent $event): void
    {
        $messageId = $event->getMessage()->getMessageId();
        $chatId = $event->getMessage()->getChat()->getId();

        try {
            match ($event->getCommand()) {
                '/start@DevscastNotifierBot', '/start' => $this->sendStartMenu($chatId, $messageId),
                '/about@DevscastNotifierBot', '/about' => $this->sendAbout($chatId, $messageId),
                '/socials@DevscastNotifierBot', '/socials' => $this->sendSocialsLinks($chatId, $messageId),
                '/rules@DevscastNotifierBot', '/rules' => $this->sendRules($chatId, $messageId),
                '/posts@DevscastNotifierBot', '/posts' => $this->sendLatestLinkFromFeed('posts', $chatId, $messageId),
                '/podcasts@DevscastNotifierBot', '/podcasts' => $this->sendLatestLinkFromFeed('podcasts', $chatId, $messageId),
            };
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
        }
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    private function sendRules(int|string $chatId, int $messageId): void
    {
        $rules = <<< RULES
Quelques règles :
 
- Bonne humeur et bienveillance
- Nous sommes tous là pour apprendre
- Pas de spam (message non relatif à la tech)
- Pas d’insultes etc..., les échanges se font dans le respect
- Aucun message publicitaire et autopromotion

Tout membre ayant un comportement suspect sera ban
RULES;

        $this->api->sendMessage($chatId, $rules, replyToMessageId: $messageId);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    private function sendSocialsLinks(int|string $chatId, int $messageId): void
    {
        $this->api->sendMessage(
            chatId: $chatId,
            text: 'Suivez-nous sur les réseaux sociaux',
            replyToMessageId: $messageId,
            replyMarkup: new InlineKeyboardMarkup(array_chunk([
                [
                    'text' => 'Twitter',
                    'url' => 'https://twitter.com/devscasttech',
                ],
                [
                    'text' => 'Linkedin',
                    'url' => 'https://cd.linkedin.com/company/devscast',
                ],
                [
                    'text' => 'Instagram',
                    'url' => 'https://www.instagram.com/devscast.tech',
                ],
                [
                    'text' => 'Facebook',
                    'url' => 'https://web.facebook.com/devscast.tech',
                ],
                [
                    'text' => 'Youtube',
                    'url' => 'https://www.youtube.com/channel/UCsvWpowwYtjfgS1BOcrX0fw',
                ],
                [
                    'text' => 'Tiktok',
                    'url' => 'https://www.tiktok.com/@devscast.tech',
                ],
            ], 2))
        );
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    private function sendLatestLinkFromFeed(string $feed, int|string $chatId, int $messageId): void
    {
        $data = $this->feedReaderService->getData($feed);
        $message = sprintf('Nos derniers %s (mise à jour le %s)', $feed, $data['last_update']);

        $this->api->sendMessage(
            chatId: $chatId,
            text: $message,
            replyToMessageId: $messageId,
            replyMarkup: new InlineKeyboardMarkup([$data['items']]),
        );
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    private function sendStartMenu(int|string $chatId, int $messageId): void
    {
        $menu = <<< MENU
Hello, I'm DevscastBot

/start : affiche ce menu
/about: à propos de devscast
/socials: Nos réseaux sociaux
/rules: les règles de la communauté
/posts: Nos derniers articles
/podcasts: Nos derniers podcasts
MENU;

        $this->api->sendMessage($chatId, $menu, replyToMessageId: $messageId);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    private function sendAbout(int|string $chatId, int $messageId): void
    {
        $about = <<< ABOUT
Devscast, 

Bâtie autour d'une communauté nous construisons un écosystème d'apprentissage,
accompagnement et recrutement de développeur grâce à la production de contenu dans le contexte local,
et activités tech ayant un fort impact.

Le but:
- Communiquer, discuter des nouvelles et des technologies.
- Organiser des réunions, inviter des intervenants et des experts.
- Créer et soutenir des projets de logiciels libres (opensource)
- Aider à la formation des nouveaux membres.

Nos plateformes:
- https://devscast.tech
- https://github.com/devscast
- https://devscast.org (building...)

Nous Contactez:
- contact@devscast.tech
ABOUT;


        $this->api->sendMessage($chatId, $about, replyToMessageId: $messageId);
    }
}
