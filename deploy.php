<?php

/**
 * @author Hipex <info@hipex.io>
 * @copyright (c) Hipex B.V. ${year}
 */
/** @noinspection PhpUnhandledExceptionInspection */

namespace HipexDeployConfiguration;

use HipexDeployConfiguration\Command\Build\Composer;
use HipexDeployConfiguration\Command\Command;
use HipexDeployConfiguration\Command\DeployCommand;

$configuration = new Configuration('git@git.bluebirdday.nl:shopware6/shopware-demo.git');

$configuration->setPhpVersion('php74');
$configuration->setPublicFolder('public');
$configuration->setSharedFiles([
    '.env',
]);

$configuration->setSharedFolders([
    'var',
    'public/thumbnail',
    'public/media',
    'config/jwt'
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
            'psalm.xml'
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
$configuration->addBuildCommand(new Command('{{bin/composer}} install -d vendor/shopware/recovery --no-interaction --optimize-autoloader --no-suggest'));
//
$configuration->addBuildCommand(new Command('./bin/build-administration.sh'));
////$configuration->addBuildCommand(new Command('./bin/build-storefront.sh'));
//
//$configuration->addBuildCommand(new DeployCommand('{{bin/php}} bin/console theme:compile'));
//$configuration->addDeployCommand(new DeployCommand('{{bin/php}} bin/console plugin:refresh'));
//$configuration->addDeployCommand(new DeployCommand('{{bin/php}} bin/console cache:clear'));

return $configuration;
