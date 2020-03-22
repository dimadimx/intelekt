<?php

namespace App\Jobs;

use App\Models\Client;
use App\Repositories\ClientRepository;
use App\Repositories\ClientStatisticRepository;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Imtigger\LaravelJobStatus\Trackable;
use Ixudra\Curl\Facades\Curl;

class Abills implements ShouldQueue {

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
    private $host = 'http://billing.intelekt.cv.ua/admin/index.cgi';

    /**
     * @var string
     */
    private $cookieFile = 'framework/cache/intelekt';

    /**
     * @var string
     */
    private $cookieDebugFile = 'framework/cache/intelekt_debug';

    /**
     * @var User
     */
    public $user;

    /**
     * @var int
     */
    public $pageRows = 10000;

    /**
     * @var string
     */
    public $lang = 'ukrainian';

    /**
     * @var null|string
     */
    public $date;

    /**
     * @var boolean
     */
    public $xml;

    /**
     * Abills constructor.
     *
     * @param \App\User   $user
     * @param null|string $date
     * @param boolean $xml
     */
    public function __construct(User $user, $date = NULL, $xml = FALSE) {
        $this->user = $user;
        $this->date = $date;
        $this->xml = $xml;
        $this->prepareStatus();
        $this->update(['input' => $this->user->id]);
    }

    /**
     * @param \App\Repositories\ClientRepository          $clientRepository
     * @param \App\Repositories\ClientStatisticRepository $clientStatisticRepository
     */
    public function handle(ClientRepository $clientRepository, ClientStatisticRepository $clientStatisticRepository) {
        if ($this->date) {
            Log::info('updateClientsSessions');
            $this->updateClientsSessions($clientRepository, $clientStatisticRepository);
        } else {
            Log::info('updateGroupClients');
            $this->setOutput(['title' => 'Clients']);
            $this->updateGroupClients($clientRepository);
            Log::info('end updateGroupClients & updateBelongsClients');
            $this->updateBelongsClients($clientRepository);
            Log::info('end updateBelongsClients');
            $this->setOutput(['total' => $this->progressNow, 'title' => 'Clients']);
        }
    }

    /**
     * @param \App\Repositories\ClientRepository          $clientRepository
     * @param \App\Repositories\ClientStatisticRepository $clientStatisticRepository
     */
    public function updateClientsSessions(ClientRepository $clientRepository, ClientStatisticRepository $clientStatisticRepository) {
        $clients = $clientRepository->findAllByAttributes(['user_id' => $this->user->id]);
        $this->setOutput(['title' => 'Clients Sessions']);
        $this->setProgressMax($clients->count());
        foreach ($clients as $key => $client) {
            $clientSessions = $this->getSessionsClientByPeriod($client);

            $data = [
                'client_id' => $client->id,
                'date' => date('Y-m-1 00:00:00', strtotime($this->date)),
            ];

            if ($this->xml) {
                $json = json_encode(simplexml_load_string($clientSessions));
                $array = json_decode($json,TRUE);
                if ( ! empty($array['TYPE']) and $array['TYPE'] == 'error') {
                    $this->auth();
                    $clientSessions = $this->getSessionsClientByPeriod($client);
                    $json = json_encode(simplexml_load_string($clientSessions));
                    $array = json_decode($json,TRUE);
                }

                if (isset($array['INFO'])) {
                    $totalTime = (int)strtr(last($array['INFO']['TOTALS_AVG']['TABLE']['DATA']['ROW'][0]['TD']), [ ' ' => '', '+' => '', ':' => '']);

                    if ( $totalTime > 0 ) {
                        $data['status'] = 1;
                    } else {
                        $data['status'] = 0;
                    }
                } else {
                    Log::warning('xml Abills:', $array);
                    $data['status'] = 2;
                }
            } else {
                if ( ! empty($clientSessions['TYPE']) and $clientSessions['TYPE'] == 'error') {
                    $this->auth();
                    $clientSessions = $this->getSessionsClientByPeriod($client);
                }

                if (isset($clientSessions['_INFO'])) {
                    if ( ! empty($clientSessions['_INFO']['__SESSIONS'])) {
                        $data['status'] = 1;
                    } else {
                        $data['status'] = 0;
                    }
                } else {
                    Log::warning('json Abills:', $clientSessions);
                    $data['status'] = 2;
                }
            }

            $clientStatistics = $clientStatisticRepository->findAllByAttributes([
                'client_id' => $client->id,
                'date >='    => date('Y-m-1 00:00:00', strtotime($this->date)),
                'date <='    => date('Y-m-t 23:59:59', strtotime($this->date))
            ]);
            Log::info('client', $data);
            if ($clientStatistics->isEmpty()) {
                $clientStatisticRepository->create($data);
                Log::info('created');
            } else {
                $clientStatisticRepository->update($data, $clientStatistics->first()->id);
                Log::info('updated');
            }

            $this->incrementProgress();
            sleep(1);
        }
        $this->setOutput(['total' => $this->progressNow, 'user_id' => $this->user->id, 'date' => $this->date, 'title' => 'Clients Sessions']);
        Log::info('end updateClientsSessions');
    }

    /**
     * @param \App\Repositories\ClientRepository $clientRepository
     */
    public function updateBelongsClients(ClientRepository $clientRepository) {
        $clients = $this->getClients(NULL, $this->user->api_uid);
        if ( ! empty($clients['TYPE']) and $clients['TYPE'] == 'error') {
            $this->auth();
            $clients = $this->getClients(NULL, $this->user->api_uid);
        }

        if ( ! empty($clients['DATA_1'])) {
            $clientRepository->updateAllByAttributes(
                ['warning' => 1], ['api_belong_uid' => $this->user->api_uid]
            );
            $this->setProgressMax($this->progressMax + count($clients['DATA_1']));
            foreach ($clients['DATA_1'] as $key => $client) {
                $foundClient = $clientRepository->findByAttribute(
                    'login', $client['login']
                );
                if ($foundClient->isEmpty()) {
                    $clientRepository->create(
                        [
                            'user_id'        => $this->user->id,
                            'login'          => $client['login'],
                            'phone'          => $client['phone'],
                            'registration'   => $client['registration'],
                            'api_gid'        => $client['gid'],
                            'api_uid'        => $client['uid'],
                            'api_belong_uid' => $this->user->api_uid,
                        ]
                    );
                } else {
                    $data = [
                        'api_gid' => $client['gid'],
                        'warning' => NULL,
                    ];
                    if ($foundClient->first()->api_belong_uid
                        != $this->user->api_uid and $client['gid']
                        != $this->user->api_gid
                    ) {
                        $data['warning']        = 1;
                        $data['api_belong_uid'] = NULL;
                    }
                    $clientRepository->update($data, $foundClient->first()->id);
                }
                $this->incrementProgress();
            }
        }
    }

    /**
     * @param \App\Repositories\ClientRepository $clientRepository
     */
    public function updateGroupClients(ClientRepository $clientRepository) {
        $clients = $this->getClients($this->user->api_gid);
        if ( ! empty($clients['TYPE']) and $clients['TYPE'] == 'error') {
            $this->auth();
            $clients = $this->getClients($this->user->api_gid);
        }

        if ( ! empty($clients['DATA_1'])) {
            $clientRepository->updateAllByAttributes(
                ['warning' => 1], ['api_gid' => $this->user->api_gid]
            );
            $this->setProgressMax(count($clients['DATA_1']));
            foreach ($clients['DATA_1'] as $key => $client) {
                $foundClient = $clientRepository->findByAttribute(
                    'login', $client['login']
                );
                if ($foundClient->isEmpty()) {
                    $clientRepository->create(
                        [
                            'user_id'      => $this->user->id,
                            'login'        => $client['login'],
                            'phone'        => $client['phone'],
                            'registration' => strtotime($client['registration']),
                            'api_gid'      => $client['gid'],
                            'api_uid'      => $client['uid'],
                        ]
                    );
                } else {
                    $data = [
                        'api_gid' => $client['gid'],
                        'warning' => 0,
                    ];
                    if ($client['gid'] != $this->user->api_gid) {
                        $data['warning'] = 1;
                    }
                    $clientRepository->update($data, $foundClient->first()->id);
                }
                $this->incrementProgress();
            }
        }
    }

    /**
     * auth into billing
     */
    private function auth() {
       Curl::to($this->host)
            ->withData(
                [
                    "DOMAIN_ID",
                    "REFERER"  => $this->host,
                    "LOGIN"    => 1,
                    "language" => $this->lang,
                    "user"     => $this->user->api_user,
                    "passwd"   => $this->user->api_password,
                    "logined",
                ]
            )
            ->setCookieJar(storage_path($this->cookieFile))
            ->allowRedirect()
            ->enableDebug(storage_path($this->cookieDebugFile))
            ->post();
    }

    /**
     * @param int|NULL $gId
     * @param int|NULL $servesId
     *
     * @return mixed
     */
    private function getClients(int $gId = NULL, int $servesId = NULL) {
        $params = [
            "qindex"         => 7,
            "search"         => 1,
            "header"         => 1,
            "type"           => 11,
            "LOGIN"          => "*",
            "json"           => 1,
            "EXPORT_CONTENT" => "USERS_LIST",
            "SKIP_FULL_INFO" => 1,
            "PAGE_ROWS"      => $this->pageRows,
        ];

        if ($gId) {
            $params['GID'] = $gId;
        }

        if ($servesId) {
            $params['_WHO_SERVES'] = $servesId;
        }

        $response = Curl::to($this->host)
            ->withData($params)
            ->setCookieFile(storage_path($this->cookieFile))
            ->allowRedirect()
            ->enableDebug(storage_path($this->cookieDebugFile))
            ->asJson(TRUE)
            ->get();

        return $response;
    }

    /**
     * @param int $id
     *
     * @return mixed
     */
    private function getClient(int $id) {
        $response = Curl::to($this->host)
            ->withData(
                [
                    "qindex"       => 15,
                    "UID"          => $id,
                    "SUMMARY_SHOW" => 1,
                    "EXPORT"       => 1,
                ]
            )
            ->setCookieFile(storage_path($this->cookieFile))
            ->allowRedirect()
            ->enableDebug(storage_path($this->cookieDebugFile))
            ->asJson(TRUE)
            ->get();

        return $response;
    }

    /**
     * @param \App\Models\Client $client
     *
     * @return mixed
     */
    private function getSessionsClientByPeriod(Client $client) {
        $params = [
            "UID"       => $client->api_uid,
            "qindex"    => 139,
            "header"    => 1,
            "FROM_DATE" => date('Y-m-1', strtotime($this->date)),
            "TO_DATE"   => date('Y-m-t', strtotime($this->date)),
            "rows"      => 3,
        ];

        if ($this->xml) {
            $params['xml'] = 1;
        } else {
            $params['json'] = 1;
        }

        $response = Curl::to($this->host)
            ->withData($params)
            ->setCookieFile(storage_path($this->cookieFile))
            ->allowRedirect()
            ->enableDebug(storage_path($this->cookieDebugFile))
            //            ->asJson(TRUE)
            ->post();

        if (!$this->xml) {
            $response = json_decode(
                str_replace('} "stats"', '}, "stats"', $response), TRUE
            );
        }

        return $response;
    }
}
