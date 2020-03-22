<?php

namespace App\Http\Controllers;

use App\DataTables\JobDataTable;
use App\Repositories\JobRepository;
use Flash;
use Illuminate\Support\Facades\Auth;
use Imtigger\LaravelJobStatus\JobStatus;
use Response;

class JobController extends AppBaseController {

    /** @var  JobRepository */
    private $jobRepository;

    public function __construct(JobRepository $jobRepository) {
        $this->jobRepository = $jobRepository;
    }

    /**
     * Display a listing
     *
     * @param JobDataTable $jobDataTable
     *
     * @return Response
     */
    public function index(JobDataTable $jobDataTable) {
        return $jobDataTable->render('jobs.index');
    }

    public function ajaxUpdate() {
        return view('jobs._ajax')
            ->with('jobs', Auth::user()->jobs->filter(function ($job) { return $job->is_executing; }));
    }
}
