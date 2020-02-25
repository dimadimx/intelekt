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
use Ixudra\Curl\Facades\Curl;

class Abills implements ShouldQueue {

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
    public $from;

    /**
     * @var null|string
     */
    public $to;

    /**
     * Abills constructor.
     *
     * @param \App\User   $user
     * @param null|string $from
     * @param null|string $to
     */
    public function __construct(User $user, $from = NULL, $to = NULL) {
        $this->user = $user;
        $this->from = $from;
        $this->to   = $to;
    }

    /**
     * @param \App\Repositories\ClientRepository          $clientRepository
     * @param \App\Repositories\ClientStatisticRepository $clientStatisticRepository
     */
    public function handle(ClientRepository $clientRepository, ClientStatisticRepository $clientStatisticRepository) {
        if ($this->from and $this->to) {
            $this->updateClientsSessions($clientRepository, $clientStatisticRepository);
        } else {
            $this->updateGroupClients($clientRepository);
            $this->updateBelongsClients($clientRepository);
        }
    }

    /**
     * @param \App\Repositories\ClientRepository          $clientRepository
     * @param \App\Repositories\ClientStatisticRepository $clientStatisticRepository
     */
    public function updateClientsSessions(ClientRepository $clientRepository, ClientStatisticRepository $clientStatisticRepository) {
        $clients = $clientRepository->findAllByAttributes(['user_id' => $this->user->id]);
        foreach ($clients as $client) {
            $clientSessions = $this->getSessionsClientByPeriod(
                $client, $this->from, $this->to
            );
            if ( ! empty($clientSessions['TYPE']) and $clientSessions['TYPE'] == 'error') {
                $this->auth();
                $clientSessions = $this->getSessionsClientByPeriod(
                    $client, $this->from, $this->to
                );
            }

            $data = [
                'client_id' => $client->id,
                'date' => date('Y-m-d 00:00:00', strtotime($this->from)),
            ];

            if ( ! empty($clientSessions['_INFO']['__SESSIONS'])) {
                $data['status'] = 1;
            } else {
                $data['status'] = 0;
            }

            $clientStatistics = $clientStatisticRepository->findAllByAttributes([
                'client_id' => $client->id,
                'date >='    => date('Y-m-d 00:00:00', strtotime($this->from)),
                'date <='    => date('Y-m-d 23:59:59', strtotime($this->to))
            ]);

            if ($clientStatistics->isEmpty()) {
                $clientStatisticRepository->create($data);
            } else {
                $clientStatisticRepository->update($data, $clientStatistics->first()->id);
            }
            sleep(2);
        }
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
            foreach ($clients['DATA_1'] as $client) {
                $foundClient = $clientRepository->findByAttribute(
                    'api_uid', $client['uid']
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
            foreach ($clients['DATA_1'] as $client) {
                $foundClient = $clientRepository->findByAttribute(
                    'api_uid', $client['uid']
                );
                if ($foundClient->isEmpty()) {
                    $clientRepository->create(
                        [
                            'user_id'      => $this->user->id,
                            'login'        => $client['login'],
                            'phone'        => $client['phone'],
                            'registration' => $client['registration'],
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
     * @param string             $from
     * @param string             $to
     *
     * @return mixed
     */
    private function getSessionsClientByPeriod(Client $client, string $from, string $to) {
        $params = [
            "UID"       => $client->api_uid,
            "qindex"    => 139,
            "header"    => 1,
            "json"      => 1,
            "FROM_DATE" => $from,
            "TO_DATE"   => $to,
            "rows"      => 3,
        ];

        $response = Curl::to($this->host)
            ->withData($params)
            ->setCookieFile(storage_path($this->cookieFile))
            ->allowRedirect()
            ->enableDebug(storage_path($this->cookieDebugFile))
            //            ->asJson(TRUE)
            ->post();

        $response = json_decode(
            str_replace('} "stats"', '}, "stats"', $response), TRUE
        );

        return $response;
    }
}
