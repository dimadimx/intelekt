<?php

namespace App\Repositories;

use App\Models\ClientStatistic;
use App\Repositories\BaseRepository;

/**
 * Class ClientStatisticRepository
 * @package App\Repositories
 * @version February 21, 2020, 3:32 pm UTC
*/

class ClientStatisticRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'client_id',
        'date',
        'status'
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
        return ClientStatistic::class;
    }
}
