<?php

namespace App\Repositories;

use Imtigger\LaravelJobStatus\JobStatus;
use App\Repositories\BaseRepository;

/**
 * Class JobRepository
 * @package App\Repositories
 * @version February 25, 2020, 8:32 am UTC
*/

class JobRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'job_id',
        'type',
        'queue',
        'progress_now',
        'progress_max',
        'status',
        'input',
        'output'
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
        return JobStatus::class;
    }
}
