<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Utility\Text;
use Cake\Http\Client;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Web3\Web3;
use Web3p\EthereumUtil\Util;
use Elliptic\EC;
use Authentication\Controller\Component\AuthenticationComponent;
use Authentication\PasswordHasher\DefaultPasswordHasher;
use Psr\Log\LogLevel;
use Cake\Event\EventInterface;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\UnauthorizedException;
use Cake\Http\Exception\NotFoundException;
use Cake\Log\Log;

class UsersController extends AppController
{
  public $Users = null;
    public $Transactions = null;
    public $Deposits = null; // ✅ Agregado para evitar el deprecated warning en PHP 8.2

    public function initialize(): void
{
    parent::initialize();
    // ... tus otros componentes
    $this->loadComponent('Authentication.Authentication');
  
    $this->loadModel('Users');       // <— así $this->Users estará siempre disponible
    $this->loadModel('Transactions'); // <— si lo usas también aquí

   
}
public function register()
{
    $this->loadModel('Users');
    $user = $this->Users->newEmptyEntity();

    if ($this->request->is('post')) {
        $data = $this->request->getData();

        // Código de referido
        $data['ref_code'] = substr(Text::uuid(), 0, 8);

        // Asignar referido si viene en la URL
        $ref = $this->request->getQuery('ref');
        if ($ref) {
            $data['referred_by'] = $ref;
        }

        // Valores iniciales
       // Valores iniciales
$data['balance'] = 0;
$data['investment_fund'] = 0;
$data['daily_profit'] = 0;
$data['referral_earnings'] = 0;
$data['investment_days_left'] = 20;
$data['last_quantified'] = null;

        
        // Crear usuario
        $user = $this->Users->patchEntity($user, $data);
        if ($this->Users->save($user)) {
            $this->Authentication->setIdentity($user);
            $userId = $user->id; // <- ID real guardado

            // Llamar al microservicio
            $http = new Client();
try {
    $response = $http->post(
        'https://mcservice-production.up.railway.app/wallet',
        json_encode(['id' => $userId]),
        [
            'headers' => ['Content-Type' => 'application/json']
        ]
    );


                if ($response->isOk()) {
                    $json = $response->getJson();
                    if (!empty($json['address'])) {
                        // Guardar wallet en el usuario
                        $user->deposit_wallet_address = $json['address'];
                        
                        $this->Users->save($user);
                    } else {
                        $this->Flash->error('Respuesta inválida del microservicio.');
                        return $this->redirect($this->referer());
                    }
                } else {
                    $this->Flash->error('Error al conectar con el microservicio.');
                    return $this->redirect($this->referer());
                }
            } catch (\Exception $e) {
                $this->Flash->error('Fallo al conectar con microservicio: ' . $e->getMessage());
                return $this->redirect($this->referer());
            }

            $this->Flash->success('Usuario registrado y wallet creada.');
            return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
        }

        // Errores de validación
        $errors = $user->getErrors();
        $msg = 'Error al guardar usuario.';
        if (!empty($errors)) {
            $msg .= ' Corrige los siguientes errores: ';
            foreach ($errors as $field => $errorList) {
                $msg .= $field . ': ' . implode(', ', $errorList) . '. ';
            }
        }
        $this->Flash->error($msg);
    }

    $this->set(compact('user'));
}

public function login()
{
    $this->request->allowMethod(['get', 'post']);
    $result = $this->Authentication->getResult();

    if ($result->isValid()) {
        $redirect = $this->request->getQuery('redirect', [
            'controller' => 'Users',
            'action' => 'dashboard'
        ]);

        return $this->redirect($redirect);
    }

    if ($this->request->is('post') && !$result->isValid()) {
        $this->Flash->error('Correo o contraseña inválidos.');
    }
}


public function logout()
{
    $result = $this->Authentication->getResult();

    if ($result->isValid()) {
        $this->Authentication->logout();
    }

    return $this->redirect(['controller' => 'Users', 'action' => 'login']);
}


public function beforeFilter(EventInterface $event)
{
    parent::beforeFilter($event);

    // Acciones que permites sin login
    $this->Authentication->allowUnauthenticated(['login',  'register']);
$this->Authentication->addUnauthenticatedActions(['deposit']);
    // Verificar si el usuario está autenticado
    $identity = $this->Authentication->getIdentity();

    // Si NO está autenticado y la acción NO está permitida, redirigir a login
    $allowed = $this->Authentication->getUnauthenticatedActions();
    if (!$identity && !in_array($this->request->getParam('action'), $allowed)) {
        return $this->redirect(['controller' => 'Users', 'action' => 'login']);
    }
}



   public function dashboard()
{

     $identity = $this->request->getAttribute('identity');
    if (!$identity) {
        return $this->redirect(['controller' => 'Users', 'action' => 'login']);
    }



    // Llamar al microservicio
   

    $user = $this->Users->get($identity->getIdentifier());
    $this->set(compact('user'));
}

public function generarHash()
{
    $hasher = new \Authentication\PasswordHasher\DefaultPasswordHasher();
    $hash = $hasher->hash('miclave123');

    debug($hash);
    die;
}
public function referidos()
{
      $identity = $this->request->getAttribute('identity');
    if (!$identity) {
        return $this->redirect(['controller' => 'Users', 'action' => 'login']);
    }
     $this->loadModel('Users');
        $user = $this->Users->get($this->Authentication->getIdentity()->id);
        
        // Renderizar el balance del usuario antes de la verificación del captcha
        $this->set('user', $user);
}


// En DepositsController.php
public function deposit()
{
    $this->autoRender = false;
    return $this->response->withStringBody("OK");
}


public function perfil()
{
      $identity = $this->request->getAttribute('identity');
    if (!$identity) {
        return $this->redirect(['controller' => 'Users', 'action' => 'login']);
    }
    $this->loadModel('Users');

    $userId = $this->Authentication->getIdentity()->id;
    $user = $this->Users->get($userId);

    if ($this->request->is('post')) {
        $monto = (float)$this->request->getData('amount');

        if ($monto <= 0) {
            $this->Flash->error('El monto debe ser mayor que cero.');
            return $this->redirect($this->referer());
        }

        if ($monto > $user->balance) {
            $this->Flash->error('No tienes suficiente balance disponible.');
            return $this->redirect($this->referer());
        }

        $user->balance -= $monto;
        $user->investment_fund += $monto;

        if ($this->Users->save($user)) {
            $this->Flash->success('Fondos movidos al fondo de inversión.');
        } else {
            $this->Flash->error('No se pudo completar la operación.');
        }
    }

    $this->set(compact('user'));
}
public function nivelesInversion()
{
    
    $this->request->allowMethod(['get']);
}
public function referidoslist()
{
    
    $user = $this->Users->get($this->Authentication->getIdentity()->id);
    $referidos = $this->Users->find()->where(['referred_by' => $user->ref_code])->all();

    $this->set(compact('user', 'referidos')); // No 'referidoslist'

}

}





