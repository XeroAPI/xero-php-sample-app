<?php
/**
 * This file contains a very crude bootstrap and routing configuration
 * Everything is currently in one file, but should be split out into providers
 */

define('DS', DIRECTORY_SEPARATOR);
define('APP_ROOT', realpath(__DIR__ . DS . '..' . DS));

include APP_ROOT . '/vendor/autoload.php';

use App\Helper\CustomExceptionStrategy;
use App\Helper\XeroSessionStorage;
use League\Container\ReflectionContainer;
use League\Plates\Engine;
use League\Route\RouteCollection;
use XeroPHP\Application;
use XeroPHP\Application\PublicApplication;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\SapiEmitter;
use Zend\Diactoros\ServerRequestFactory;

$container = new League\Container\Container();

/**
 * Container has the power to automatically resolve objects and
 * all of their dependencies recursively by inspecting the type hints
 * of the constructor arguments
 *
 * @see  http://container.thephpleague.com/3.x/auto-wiring/
 */
$container->delegate(new ReflectionContainer());

//Share the Session storage class across dependents
$container->share(XeroSessionStorage::class);

//Share the template engine into controllers instantiated by the container
$container->share(Engine::class, function () {
    return new Engine(APP_ROOT . DS . 'src' . DS . 'templates', 'phtml');
});


// This is where the Xero application is instantiated.
// This should happen wherever your services are registered
$container->share(Application::class, function () use ($container) {
    $config_path = APP_ROOT . DS . 'config' . DS . 'xero.php';

    if (!file_exists($config_path) || !is_readable($config_path)) {
        throw new Exception(sprintf('[%s] is either missing or not readable', $config_path));
    }

    $application = new PublicApplication(include $config_path);

    /** @var XeroSessionStorage $sessionStorage */
    $sessionStorage = $container->get(XeroSessionStorage::class);

    //If the session exists, register it on the application
    if (null !== $session = $sessionStorage->getSession()) {
        $application->getOAuthClient()
            ->setToken($session->token)
            ->setTokenSecret($session->token_secret);
    }

    return $application;
});

//Routes
$collection = new RouteCollection($container);

//Overload the exception handling
$collection->setStrategy(new CustomExceptionStrategy());

\App\Controller\ApplicationController::registerRoutes($collection);
\App\Controller\AccountsController::registerRoutes($collection);
\App\Controller\BankTransactionsController::registerRoutes($collection);
\App\Controller\BankTransfersController::registerRoutes($collection);
\App\Controller\BrandingThemesController::registerRoutes($collection);
\App\Controller\ContactsController::registerRoutes($collection);
\App\Controller\ContactGroupsController::registerRoutes($collection);


$request = ServerRequestFactory::fromGlobals($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);

$response = $collection->dispatch($request, new Response());

$emitter = new SapiEmitter();
$emitter->emit($response);