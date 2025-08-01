<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     3.3.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App;

use Cake\Core\Configure;
use Cake\Core\ContainerInterface;
use Cake\Datasource\FactoryLocator;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\BaseApplication;
use Cake\Http\Middleware\BodyParserMiddleware;
use Cake\Http\Middleware\CsrfProtectionMiddleware;
use Cake\Http\MiddlewareQueue;
use Cake\ORM\Locator\TableLocator;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;
use Authentication\Middleware\AuthenticationMiddleware;

use Authentication\AuthenticationService;
use Authentication\AuthenticationServiceInterface;

use Psr\Http\Message\ServerRequestInterface;

use Authentication\AuthenticationServiceProviderInterface;



/**
 * Application setup class.
 *
 * This defines the bootstrapping logic and middleware layers you
 * want to use in your application.
 */
class Application extends BaseApplication implements AuthenticationServiceProviderInterface

{
    /**
     * Load all the application configuration and bootstrap logic.
     *
     * @return void
     */
 public function bootstrap(): void
    {
        parent::bootstrap();
if (PHP_SAPI === 'cli') {
        $this->addPlugin('Bake');
    }
        $this->addPlugin('Migrations');
    }

    public function bootstrapCli(): void
    {
        // Puedes dejarlo vacío o con cosas CLI específicas
    
}
 public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
{
    $middlewareQueue
 ->add(new AuthenticationMiddleware($this))
 
 ->add(new ErrorHandlerMiddleware(Configure::read('Error')))
        ->add(new AssetMiddleware([
            'cacheTime' => Configure::read('Asset.cacheTime')
        ]))
        ->add(new RoutingMiddleware($this))
        ->add(new BodyParserMiddleware());
        
        
       

        // 👇 Asegúrate de que esté este middleware


        
      
 
        
        // 👇 ESTE ES EL IMPORTANTE
        

    return $middlewareQueue;
}
     public function getAuthenticationService(ServerRequestInterface $request): AuthenticationServiceInterface
    {
        $service = new AuthenticationService();
        
   $service->setConfig([
        'unauthenticatedRedirect' => '/users/login',  // aquí se define la redirección si no está autenticado
        'queryParam' => 'redirect',
    ]);

    $fields = [
        'username' => 'email',
        'password' => 'password',
    ];

    $service->loadIdentifier('Authentication.Password', [
        'fields' => $fields
    ]);

    $service->loadAuthenticator('Authentication.Session');
    $service->loadAuthenticator('Authentication.Form', [
        'fields' => $fields,
        'loginUrl' => '/users/login',
    ]);

    return $service;
}
}