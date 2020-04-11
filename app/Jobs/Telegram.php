<?php

namespace App\Jobs;

use App\Repositories\ClientRepository;
use App\Repositories\ClientSignalRepository;
use App\User;
use danog\MadelineProto\API;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Imtigger\LaravelJobStatus\Trackable;

class Telegram implements ShouldQueue {

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Trackable;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 86000;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;


    /**
     * @var string
     */
    private $cookieFile = 'framework/cache/telegram';

    /**
     * @var User
     */
    public $user;

    /**
     * @var \danog\MadelineProto\API
     */
    public $madeline;

    /**
     * @var null|string
     */
    public $date;

    /**
     * Abills constructor.
     *
     * @param \App\User   $user
     * @param null|string $date
     * @param boolean $xml
     */
    public function __construct(User $user) {
        $this->user = $user;
        $this->date = date('Y-m-d');
        $this->prepareStatus();
        $this->update(['input' => $this->user->id]);
    }

    /**
     * @param \App\Repositories\ClientRepository       $clientRepository
     * @param \App\Repositories\ClientSignalRepository $clientSignalRepository
     */
    public function handle(ClientRepository $clientRepository, ClientSignalRepository $clientSignalRepository) {
        Log::info('updateClientsSignal');
        $this->auth();
        $this->updateClientsSignal($clientRepository, $clientSignalRepository);
    }

    /**
     * @param \App\Repositories\ClientRepository       $clientRepository
     * @param \App\Repositories\ClientSignalRepository $clientSignalRepository
     */
    public function updateClientsSignal(ClientRepository $clientRepository, ClientSignalRepository $clientSignalRepository) {
        $clients = $clientRepository->findAllByAttributes(['user_id' => $this->user->id]);
        $this->setOutput(['title' => 'Clients Signals']);
        $this->setProgressMax($clients->count());
        foreach ($clients as $key => $client) {
            $data = [
                'client_id' => $client->id,
                'date' => date('Y-m-d', strtotime($this->date)),
            ];

            $this->madeline->messages->sendMessage(['peer' => "@IntelektWorkBot", 'message' => "<code>/userinfo_{$client->login}</code>", 'parse_mode' => 'HTML']);
            sleep(10);
            $response = $this->madeline->messages->getHistory([
                'peer' => "@IntelektWorkBot", //название_канала, должно начинаться с @, например @breakingmash
                'offset_id' => 0,
                'offset_date' => 0,
                'add_offset' => 0,
                'limit' => 10, //Количество постов, которые вернет клиент
                'max_id' => 0, //Максимальный id поста
                'min_id' => 0, //Минимальный id поста - использую для пагинации, при  0 возвращаются последние посты.
                //'hash' => []
            ]);

            $filteredMessage = NULL;
            foreach ($response['messages'] as $message) {
                if (strpos($message['message'], 'Рівень сигналу')) {
                    $filteredMessage = $message['message'];
                }
                if (strpos($message['message'], $client->login) and !is_null($filteredMessage)) {
                    $filteredMessage = preg_split('/Рівень сигналу:/', $filteredMessage);
                    $filteredMessage = preg_split('/IP:/', $filteredMessage[1]);
                    $filteredMessage = preg_split('/Сесія відсутня/', $filteredMessage[0]);
                    $data['value'] = trim($filteredMessage[0]);
                    $clientSignal = $clientSignalRepository->findAllByAttributes([
                        'client_id' => $client->id,
                        'date >='    => date('Y-m-d 00:00:00', strtotime($this->date)),
                        'date <='    => date('Y-m-d 23:59:59', strtotime($this->date))
                    ]);
                    Log::info('client signal', $data);
                    if ($clientSignal->isEmpty()) {
                        $clientSignalRepository->create($data);
                        Log::info('created');
                    } else {
                        $clientSignalRepository->update($data, $clientSignal->first()->id);
                        Log::info('updated');
                    }
                    break;
                }
            }

            $this->incrementProgress();
        }
        $this->setOutput(['total' => $this->progressNow, 'user_id' => $this->user->id, 'title' => 'Clients Signals']);
        Log::info('end updateClientsSignal');
    }

    /**
     * auth to telegram
     */
    private function auth() {
        // Если файл с сессией уже существует, использовать его
        if(file_exists( storage_path($this->cookieFile) ) ) {
            $madeline = new API( storage_path($this->cookieFile) );
            // Запросить код с помощью консоли
            //            $code = 35973;//readline('Enter the code you received: ');
            //            $madeline->complete_phone_login($code);
        } else {
            // Иначе создать новую сессию
            $madeline = new API(storage_path($this->cookieFile), [
                'app_info' => [
                    'api_id' => 999552,
                    'api_hash' => 'f4693905c15b2868f6682e9df5a13cf9',
                ]
            ]);

            // Задать имя сессии
            $madeline->session = storage_path($this->cookieFile);

            // Принудительно сохранить сессию
            $madeline->serialize();

            // Начать авторизацию по номеру мобильного телефона
            $madeline->phone_login( '+380509853357' );
            // Запросить код с помощью консоли
            $code = readline('Enter the code you received: ');
            $madeline->complete_phone_login($code);
        }

        $this->madeline = $madeline;
    }
}
