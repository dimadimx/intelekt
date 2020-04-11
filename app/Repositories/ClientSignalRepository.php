<?php

namespace App\Repositories;

use App\Models\ClientSignal;
use App\Repositories\BaseRepository;

/**
 * Class ClientSignalRepository
 * @package App\Repositories
 * @version April 11, 2020, 2:47 pm EEST
*/

class ClientSignalRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'client_id',
        'date',
        'value'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return ClientSignal::class;
    }
}
