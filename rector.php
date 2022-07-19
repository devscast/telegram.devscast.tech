<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Set\ValueObject\SetList;
use Rector\Symfony\Set\SymfonySetList;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters
        ->set(Option::PATHS, [
            __DIR__ . '/src',
        ])->set(
            Option::SYMFONY_CONTAINER_XML_PATH_PARAMETER,
            __DIR__ . '/var/cache/dev/App_KernelDevDebugContainer.xml'
        );

    // symfony
    $containerConfigurator->import(SymfonySetList::SYMFONY_52);
    $containerConfigurator->import(SymfonySetList::SYMFONY_CODE_QUALITY);
    $containerConfigurator->import(SymfonySetList::ANNOTATIONS_TO_ATTRIBUTES);
    $containerConfigurator->import(SymfonySetList::SYMFONY_CONSTRUCTOR_INJECTION);

    // Define what rule sets will be applied
    $containerConfigurator->import(SetList::CODE_QUALITY);
    $containerConfigurator->import(SetList::CODING_STYLE);
    $containerConfigurator->import(SetList::DEAD_CODE);
    $containerConfigurator->import(SetList::PHP_81);
    $containerConfigurator->import(SetList::PRIVATIZATION);

    // get services (needed for register a single rule)
    $services = $containerConfigurator->services();
    $services->set(Rector\Php74\Rector\Property\TypedPropertyRector::class);
    $services->set(Rector\Privatization\Rector\Class_\FinalizeClassesWithoutChildrenRector::class);

    // removes rules from set
    $services->remove(Rector\CodingStyle\Rector\Catch_\CatchExceptionNameMatchingTypeRector::class);
    $services->remove(Rector\Privatization\Rector\Class_\RepeatedLiteralToClassConstantRector::class);
    $services->remove(Rector\Privatization\Rector\Property\ChangeReadOnlyPropertyWithDefaultValueToConstantRector::class);
};
