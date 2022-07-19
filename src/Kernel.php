<?php

declare(strict_types=1);

namespace App;

use App\Service\Telegram\CommandHandlerInterface;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

final class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    protected function build(ContainerBuilder $container): void
    {
        $container
            ->registerForAutoconfiguration(CommandHandlerInterface::class)
            ->addTag('telegram.command_handler');
        parent::build($container);
    }
}
