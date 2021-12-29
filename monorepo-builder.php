<?php
// @codingStandardsIgnoreStart
declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\PushNextDevReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\PushTagReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\SetCurrentMutualDependenciesReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\SetNextMutualDependenciesReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\TagVersionReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\UpdateBranchAliasReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\UpdateReplaceReleaseWorker;
use Symplify\MonorepoBuilder\ValueObject\Option;
// @codingStandardsIgnoreEnd

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PACKAGE_DIRECTORIES, [__DIR__ . '/packages']);
    $parameters->set(
        Option::PACKAGE_DIRECTORIES_EXCLUDES,
        [__DIR__ . '/packages/DEBIAN']
    );
    $parameters->set(Option::DEFAULT_BRANCH_NAME, 'monorepo-release');
    $parameters->set(
        Option::DATA_TO_APPEND,
        [
            ComposerJsonSection::REPLACE => [
                'laminas/laminas-cache-storage-adapter-apc' => '*',
                'laminas/laminas-cache-storage-adapter-dba' => '*',
                'laminas/laminas-cache-storage-adapter-memcache' => '*',
                'laminas/laminas-cache-storage-adapter-mongodb' => '*',
                'laminas/laminas-cache-storage-adapter-wincache' => '*',
                'laminas/laminas-cache-storage-adapter-xcache' => '*',
            ]
        ]
    );

    $services = $containerConfigurator->services();
    $services->set(UpdateReplaceReleaseWorker::class);
    $services->set(SetCurrentMutualDependenciesReleaseWorker::class);
    $services->set(TagVersionReleaseWorker::class);
    $services->set(PushTagReleaseWorker::class);
    $services->set(SetNextMutualDependenciesReleaseWorker::class);
    $services->set(UpdateBranchAliasReleaseWorker::class);
    $services->set(PushNextDevReleaseWorker::class);
};
