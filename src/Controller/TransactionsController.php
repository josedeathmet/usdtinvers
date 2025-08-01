<?php
declare(strict_types=1);

namespace App\Controller;


use UsersController;
use Cake\ORM\TableRegistry;
use Cake\Http\Client;
use Web3\Web3;
use Web3\Contract;
use Web3\Providers\HttpProvider;
use Web3\RequestManagers\HttpRequestManager;
use Cake\Utility\Text;
use Cake\I18n\FrozenDate;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Web3p\EthereumUtil\Util;
use Elliptic\EC;
use Authentication\Controller\Component\AuthenticationComponent;
use Authentication\PasswordHasher\DefaultPasswordHasher;
use Psr\Log\LogLevel;










use Cake\Event\EventInterface;

class TransactionsController extends AppController
{
     public $Users = null;
    public $Transactions = null;

    public function initialize(): void
{
    parent::initialize();
    // ... tus otros componentes
    $this->loadComponent('Authentication.Authentication');
    $this->loadModel('Users');       // <— así $this->Users estará siempre disponible
    $this->loadModel('Transactions'); // <— si lo usas también aquí
 $this->loadComponent('Authentication.Authentication');
   
}
public function history()
{
    $identity = $this->request->getAttribute('identity');
    if (!$identity) {
        return $this->redirect(['controller' => 'Users', 'action' => 'login']);
    }

    $userId = $identity->id;

$transactions = $this->Transactions->find()
    ->where(['user_id' => $identity->id, 'type' => 'withdraw'])
    ->order(['created' => 'DESC'])
    ->toArray(); // <--- Esto convierte el resultado en array
    

    $this->set(compact('transactions'));
}

public function deposit()
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


    public function withdraw()

{
      $identity = $this->request->getAttribute('identity');
    if (!$identity) {
        return $this->redirect(['controller' => 'Users', 'action' => 'login']);
    }
    $this->request->allowMethod(['get', 'post']);

    $user = $this->Users->get($this->Authentication->getIdentity()->id);
if ($this->request->is('get')) {
    $withdrawals = $this->Transactions->find()
        ->where(['user_id' => $user->id, 'type' => 'withdraw'])
        ->order(['created' => 'DESC'])
        ->all();

    $this->set(compact('user', 'withdrawals'));
    return;
}

    if ($this->request->is('get')) {
        $this->set(compact('user'));
        return;
    }

    $amount = (float)$this->request->getData('amount');

    if ($amount < 0.1) {
        $this->Flash->error('El retiro mínimo es de 0.1 USDT.');
        return $this->redirect($this->referer());
    }

    if ($amount > $user->balance) {
        $this->Flash->error('Fondos insuficientes.');
        return $this->redirect($this->referer());
    }

$ultimoRetiro = $this->Transactions->find()
    ->where(['user_id' => $user->id, 'type' => 'withdraw'])
    ->order(['created' => 'DESC'])
    ->first();

$hoy = FrozenDate::now()->format('Y-m-d');

if ($ultimoRetiro && $ultimoRetiro->created->format('Y-m-d') === $hoy) {
    $this->Flash->error('Solo puedes hacer un retiro por día. Intenta nuevamente después de las 12:00 AM.');
    return $this->redirect($this->referer());
}


    // 👉 Llamar al microservicio para hacer el retiro real
    $http = new Client();
   $response = $http->post(
    'https://mcservice-production.up.railway.app/retirar',
    json_encode([
        'userId' => $user->id,
        'to' => $user->withdraw_wallet_address,
        'amount' => (float)$amount
    ]),
    [
        'headers' => ['Content-Type' => 'application/json']
    ]
);


    if (!$response->isOk()) {
        $this->Flash->error('❌ Error procesando el retiro: ' . $response->getStringBody());
        return $this->redirect($this->referer());
    }

    $result = $response->getJson();
    $txHash = $result['txHash'] ?? null;

    if (!$txHash) {
        $this->Flash->error('❌ El retiro no fue confirmado.');
        return $this->redirect($this->referer());
    }

    // Actualizar el usuario
    $user->balance -= $amount;
    $user->daily_profit = 0;
    $this->Users->save($user);

    // Registrar transacción
    $tx = $this->Transactions->newEntity([
        'user_id' => $user->id,
        'type' => 'withdraw',
        'amount' => $amount,
        'status' => 'completed',
        'tx_hash' => $txHash
    ]);
    $this->Transactions->save($tx);

    $this->Flash->success('✅ Retiro enviado con éxito.');
    return $this->redirect(['action' => 'withdraw']);
}
   
    
    private function recompensarReferidos($userId, $amount)
{
    $Users = TableRegistry::getTableLocator()->get('Users');
    $user = $Users->get($userId);

    $niveles = [0.10, 0.03, 0.01];
    $codigo = $user->referred_by;

    for ($i = 0; $i < 3 && $codigo; $i++) {
        $ref = $Users->find()->where(['ref_code' => $codigo])->first();
        if ($ref) {
            $ganancia = $amount * $niveles[$i];
            $ref->balance += $ganancia;
            $ref->referral_earnings += $ganancia;
            $Users->save($ref);

            // (Opcional) Log para depurar
            Log::write('info', "Nivel " . ($i + 1) . ": +$ganancia USDT para " . $ref->username);

            // Siguiente nivel
            $codigo = $ref->referred_by;
        } else {
            break;
        }
    }
}


    public function generarGananciasDiarias()
    {
        $this->request->allowMethod(['post']);
        $token = $this->request->getQuery('token');

        if ($token !== 'tu_token_secreto') {
            $this->Flash->error('Token inválido.');
            return $this->redirect($this->referer());
        }

        $Users = TableRegistry::getTableLocator()->get('Users');
        $usuarios = $Users->find()->all();

        foreach ($usuarios as $user) {
            $ganancia = $user->investment_fund * 0.20; // 1% diario
            $user->balance += $ganancia;
            $user->daily_profit = $ganancia;
            if ($this->Users->save($user)) {
    return $this->redirect(['action' => 'cuantificar']);
}

        }

        $this->Flash->success('Ganancias generadas.');
        return $this->redirect($this->referer());
    }
   

public function cuantificar()
{
    $identity = $this->request->getAttribute('identity');
    if (!$identity) {
        return $this->redirect(['controller' => 'Users', 'action' => 'login']);
    }

    $user = $this->Users->get($this->Authentication->getIdentity()->id);

    if ($this->request->is('post')) {
        $hoy = FrozenDate::now()->format('Y-m-d');
        $ultima = $user->last_quantified ? $user->last_quantified->format('Y-m-d') : null;

        if ($ultima === $hoy) {
            $this->Flash->error('Ya has cuantificado tus ganancias hoy. Intenta mañana.');
            return $this->redirect($this->referer());
        }

        if ($user->investment_days_left <= 0) {
            // Cuando termina el ciclo, borrar fondos y avisar
            $user->investment_fund = 0;
            $user->investment_days_left = 0;
            $user->daily_profit = 0;
            $user->last_quantified = null;
            $this->Users->save($user);

            $this->Flash->info('Tu ciclo terminó. Haz una nueva inversión para reiniciar.');
            return $this->redirect($this->referer());
        }

        // Si está activo, calcular nivel y porcentaje
        $fondos = $user->investment_fund;
        if ($fondos >= 500) {
            $nivel = 4;
            $porcentaje = 0.22;
        } elseif ($fondos >= 200) {
            $nivel = 3;
            $porcentaje = 0.21;
        } elseif ($fondos >= 50) {
            $nivel = 2;
            $porcentaje = 0.20;
        } elseif ($fondos >= 10) {
            $nivel = 1;
            $porcentaje = 0.18;
        } else {
            $this->Flash->error('Necesitas al menos 10 USDT para activar el contrato.');
            return $this->redirect($this->referer());
        }

        // Calcular ganancia diaria y descontar un día
        $ganancia = $fondos * $porcentaje;
        $user->balance += $ganancia;
        $user->daily_profit = $ganancia;
        $user->last_quantified = FrozenDate::now();
        $user->investment_days_left = max(0, $user->investment_days_left - 1);

        $this->Users->save($user);

        $this->Flash->success(number_format($ganancia, 2) . ' USDT ganados hoy.');
        return $this->redirect($this->referer());
    }

    $this->set(compact('user'));
}

public function reinvertir()
{
    $identity = $this->request->getAttribute('identity');
    if (!$identity) {
        return $this->redirect(['controller' => 'Users', 'action' => 'login']);
    }

    $user = $this->Users->get($identity->id);

    if ($user->investment_fund < 10) {
        $this->Flash->error('La inversión mínima es de 10 USDT para reinvertir.');
        return $this->redirect($this->referer());
    }

    // Reiniciar ciclo y borrar fondo
    $user->investment_days_left = 20;
    $user->last_quantified = null;
    $user->investment_fund = 0;

    if ($this->Users->save($user)) {
        $this->Flash->success('✅ Reinversión activada. Ciclo reiniciado y fondo borrado.');
    } else {
        $this->Flash->error('❌ No se pudo reinvertir.');
        debug($user->getErrors()); // Mostrará por qué falló
    }

    return $this->redirect(['action' => 'cuantificar']);
}



public function beforeFilter(EventInterface $event)
{
    parent::beforeFilter($event);

    // Acciones que permites sin login
    $this->Authentication->allowUnauthenticated(['login', 'add', 'register']);

    // Verificar si el usuario está autenticado
    $identity = $this->Authentication->getIdentity();

    // Si NO está autenticado y la acción NO está permitida, redirigir a login
    $allowed = $this->Authentication->getUnauthenticatedActions();
   if (!$identity && !in_array($this->request->getParam('action'), $allowed)) {
    return $this->redirect(['controller' => 'Users', 'action' => 'login']);
}

}



}