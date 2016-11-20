<?php
use Silex\Application;
use Rpodwika\Silex\YamlConfigServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\AssetServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;
use Silex\Provider\SecurityServiceProvider;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Services\UserProvider;

$app = new Application();
$app
    ->register(new YamlConfigServiceProvider(__DIR__ . '/../config/config.yml'))
    ->register(new DoctrineServiceProvider(), [
        'db.options' => [
            'driver' => $app['config']['config']['driver'],
            'host' => $app['config']['config']['host'],
            'user' => $app['config']['config']['db_user'],
            'dbname' => $app['config']['config']['db_name'],
            'password' => $app['config']['config']['db_password'],
            'charset' => 'utf8mb4',
        ],
    ])
    ->register(new SecurityServiceProvider(), [
        'security.firewalls' => [
            'root' => [
                'pattern' => $app['config']['config']['root.pattern'],
                'form' => array(
                    'login_path' => $app['config']['config']['root.login_path'],
                    'check_path' => $app['config']['config']['root.check_path'],
                    'always_use_default_target_path' => true,
                    'default_target_path' => $app['config']['config']['root.redirect_path']
                ),
                'users' => array(
                    $app['config']['config']['root.username'] => array(
                        $app['config']['config']['root.role'],
                        password_hash($app['config']['config']['root.password'], PASSWORD_BCRYPT)
                    ),
                ),
                'logout' => [
                    'logout_path' => $app['config']['config']['root.logout_path'],
                    'invalidate_session' => true
                ],
            ],
            'admin' => [
                'pattern' => '^/dashboard',
                'form' => [
                    'login_path' => '/admin',
                    'check_path' => '/dashboard/login_check',
                    'always_use_default_target_path' => true,
                    'default_target_path' => '/dashboard',
                ],
                'logout' => [
                    'logout_path' => '/dashboard/logout',
                    'invalidate_session' => true
                ],
                'users' => function () use ($app) {
                    return new UserProvider($app['db']);
                }
            ],
        ],
    ])
//    ->register(new SessionServiceProvider(), [
//        'security.firewalls' => [
//            'root' => array(
//                'pattern' => $app['config']['config']['root.pattern'],
//                'form' => array(
//                    'login_path' => $app['config']['config']['root.login_path'],
//                    'check_path' => $app['config']['config']['root.check_path'],
//                    'always_use_default_target_path' => true,
//                    'default_target_path' => $app['config']['config']['root.redirect_path']
//                ),
//                'users' => array(
//                    $app['config']['config']['root.username'] => array(
//                        $app['config']['config']['root.role'],
//                        password_hash($app['config']['config']['root.password'], PASSWORD_BCRYPT)
//                    ),
//                ),
//                'logout' => [
//                    'logout_path' => $app['config']['config']['root.logout_path'],
//                    'invalidate_session' => true
//                ],
//            ),
//            'admin' => [
//                'pattern' => '^/dashboard',
//                'form' => [
//                    'login_path' => '/admin',
//                    'check_path' => '/dashboard/login_check',
//                    'always_use_default_target_path' => true,
//                    'default_target_path' => '/dashboard',
//                ],
//                'logout' => [
//                    'logout_path' => '/dashboard/logout',
//                    'invalidate_session' => true
//                ],
//                'users' => function () use ($app) {
//                    return new UserProvider($app['db']);
//                }
//            ],
//        ],
//    ])
    ->register(new AssetServiceProvider(), [
        'assets.version_format' => '%s?version=%s',
        'assets.named_packages' => array(
            'css' => [
                'base_path' => __DIR__ . '/../web/css',
            ],
            'images' => [
                'base_path' => __DIR__ . '/../web/img',
            ],
            'js' => [
                'base_path' => __DIR__ . '/../web/js',
            ],
        ),
    ])
    ->register(new MonologServiceProvider(), [
        'monolog.logfile' => __DIR__ . '/../logs/syslog-'.date('d-m-Y').'.log'
    ])
	->register(new HttpFragmentServiceProvider())
    ->register(new SessionServiceProvider())
    ->register(new TwigServiceProvider(), [
        'twig.path' => __DIR__ . '/../tpl',
    ]);

return $app;