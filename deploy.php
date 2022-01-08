<?php

/**
 * @author Hipex <info@hipex.io>
 * @copyright (c) Hipex B.V. ${year}
 */
/** @noinspection PhpUnhandledExceptionInspection */

namespace HipexDeployConfiguration;

use HipexDeployConfiguration\Command\Build\Composer;
use HipexDeployConfiguration\Command\Build\Shopware6\PluginRefresh;
use HipexDeployConfiguration\Command\Build\Shopware6\ShopwareRecovery;
use HipexDeployConfiguration\Command\Command;
use HipexDeployConfiguration\Command\DeployCommand;

use HipexDeployConfiguration\Command\Build\Shopware6\BuildAdministration;
use HipexDeployConfiguration\Command\Build\Shopware6\BuildStorefront;
use HipexDeployConfiguration\Command\Build\Shopware6\ThemeCompile;


$configuration = new Configuration('git@git.bluebirdday.nl:shopware6/shopware-demo.git');

$configuration->setPhpVersion('php74');
$configuration->setPublicFolder('public');
$configuration->setSharedFiles([
    '.env',
]);

$configuration->setSharedFolders([
    'custom/plugins',
    'config/jwt',
    'files',
    'var/log',
    'public/media',
    'public/thumbnail',
    'public/sitemap',
]);

$configuration->setDeployExclude(
    array_merge(
        $configuration->getDeployExclude(),
        [
            'code_quality_check.sh',
            'auth.json.sample',
            'CHANGELOG.md',
            'composer-cache',
            'COPYING.txt',
            '.editorconfig',
            'grunt-config.json.sample',
            'Gruntfile.js.sample',
            '.htaccess',
            '.htaccess.sample',
            'LICENSE_AFL.txt',
            'LICENSE_EE.txt',
            'LICENSE.txt',
            'nginx.conf.sample',
            'package.json.sample',
            '.php_cs.dist',
            'phpserver',
            'README_EE.md',
            'SECURITY.md',
            '.travis.yml.sample',
            'build_frontend_dev.sh',
            'local-php-security-checker',
            'send_performance_annotation',
            'php-security-checker',
            'phpstan.neon',
            'build_fe.sh',
            'README.md',
            'postpull.sh',
            'magepack.config.js',
            'vendor/snowdog/frontools',
            '.dockerignore',
            'docker-compose.yml',
            'Dockerfile',
            'easy-coding-standard.php',
            'license.txt',
            'phpstan.neon',
            'PLATFORM_COMMIT_SHA',
            'psalm.xml',
            'phpunit.xml.dist',
            'env.dist',
            'var/cache/*'
        ]
    )
);

$productionStage = $configuration->addStage('production', 'shopware.bluebirdday.io', 'shopware');
$productionStage->addServer('production1038.hipex.io');

$composerInstallArguments = [
    '--verbose',
    '--no-progress',
    '--no-interaction',
    '--optimize-autoloader',
    '--ignore-platform-reqs',
];
$configuration->addBuildCommand(new Command('ls'));
$configuration->addBuildCommand(new Composer($composerInstallArguments));
$configuration->addBuildCommand(new ShopwareRecovery());

$configuration->addBuildCommand(new BuildAdministration());
$configuration->addBuildCommand(new BuildStorefront());
$configuration->addBuildCommand(new ThemeCompile());
$configuration->addBuildCommand(new PluginRefresh());


$configuration->addDeployCommand(new DeployCommand('{{bin/php}} bin/console database:migrate --all'));
$configuration->addDeployCommand(new DeployCommand('{{bin/php}} bin/console cache:clear'));
$configuration->addDeployCommand(new DeployCommand('supervisorctl -c /etc/supervisor/$(whoami).conf restart all'));
$configuration->addDeployCommand(new DeployCommand('{{bin/php}} bin/console cache:warmup'));
$configuration->addDeployCommand(new DeployCommand('{{bin/php}} bin/console http:cache:warm:up'));

return $configuration;
