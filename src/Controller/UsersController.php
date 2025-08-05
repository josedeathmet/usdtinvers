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



  //ahora
   

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






public function deposit()
{
    $this->request->allowMethod(['post']);
    $apiKey = $this->request->getHeaderLine('x-api-key');

    if ($apiKey !== env('DEPOSITO_TOKEN')) {
        Log::write('error', '🔒 API Key inválida: ' . $apiKey);
        return $this->response->withStatus(403)->withStringBody('Invalid API key');
    }

    $data = $this->request->getData();
    Log::write('debug', '📥 Datos recibidos en webhook de depósito: ' . json_encode($data));

    $userId = $data['user_id'] ?? null;
    $amount = $data['amount'] ?? null;
    $txHash = $data['tx_hash'] ?? null;

    if (!$userId || !$amount || !$txHash) {
        Log::write('error', '❌ Faltan parámetros: ' . json_encode($data));
        return $this->response->withStatus(400)->withStringBody('Missing parameters');
    }

    try {
        // Verificar y obtener usuario
        $user = $this->Users->find()->where(['id' => $userId])->first();
        if (!$user) {
            Log::write('error', "🧍 Usuario no encontrado: {$userId}");
            return $this->response->withStatus(404)->withStringBody('User not found');
        }

        // Aquí podrías implementar idempotencia por tx_hash: guardar en una tabla de depósitos procesados
        // y si ya existe ese txHash para ese userId, devolver éxito sin volver a aplicar.

        // Sumar el fondo de inversión (asegurar cast float)
        $user->investment_fund = (float)$user->investment_fund + (float)$amount;

        if (!$this->Users->save($user)) {
            Log::write('error', "❌ Falló al guardar el usuario {$userId} con nuevo balance.");
            return $this->response->withStatus(500)->withStringBody('Error saving user');
        }

        Log::write('info', "💰 Depósito aplicado: +{$amount} USDT a user_id={$userId}, tx={$txHash}");

        // Intentar recompensar referidos, pero no hacer que falle todo si esa parte da error
        try {
            $this->recompensarReferidos($userId, $amount);
        } catch (\Throwable $e) {
            Log::write('error', "⚠️ Error en recompensarReferidos para user {$userId}: " . $e->getMessage());
            // opcional: puedes seguir y devolver éxito igualmente
        }

        return $this->response->withType('application/json')
            ->withStringBody(json_encode(['status' => 'success']));
    } catch (\Throwable $e) {
        Log::write('error', '💥 Excepción general en webhook de depósito: ' . $e->getMessage());
        return $this->response->withStatus(500)->withStringBody('Error processing deposit');
    }
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
