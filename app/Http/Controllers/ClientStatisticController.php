<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateClientStatisticRequest;
use App\Http\Requests\UpdateClientStatisticRequest;
use App\Repositories\ClientStatisticRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class ClientStatisticController extends AppBaseController
{
    /** @var  ClientStatisticRepository */
    private $clientStatisticRepository;

    public function __construct(ClientStatisticRepository $clientStatisticRepo)
    {
        $this->clientStatisticRepository = $clientStatisticRepo;
    }

    /**
     * Display a listing of the ClientStatistic.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $clientStatistics = $this->clientStatisticRepository->all();

        return view('client_statistics.index')
            ->with('clientStatistics', $clientStatistics);
    }

    /**
     * Show the form for creating a new ClientStatistic.
     *
     * @return Response
     */
    public function create()
    {
        return view('client_statistics.create');
    }

    /**
     * Store a newly created ClientStatistic in storage.
     *
     * @param CreateClientStatisticRequest $request
     *
     * @return Response
     */
    public function store(CreateClientStatisticRequest $request)
    {
        $input = $request->all();

        $clientStatistic = $this->clientStatisticRepository->create($input);

        Flash::success('Client Statistic saved successfully.');

        return redirect(route('clientStatistics.index'));
    }

    /**
     * Display the specified ClientStatistic.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $clientStatistic = $this->clientStatisticRepository->find($id);

        if (empty($clientStatistic)) {
            Flash::error('Client Statistic not found');

            return redirect(route('clientStatistics.index'));
        }

        return view('client_statistics.show')->with('clientStatistic', $clientStatistic);
    }

    /**
     * Show the form for editing the specified ClientStatistic.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $clientStatistic = $this->clientStatisticRepository->find($id);

        if (empty($clientStatistic)) {
            Flash::error('Client Statistic not found');

            return redirect(route('clientStatistics.index'));
        }

        return view('client_statistics.edit')->with('clientStatistic', $clientStatistic);
    }

    /**
     * Update the specified ClientStatistic in storage.
     *
     * @param int $id
     * @param UpdateClientStatisticRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateClientStatisticRequest $request)
    {
        $clientStatistic = $this->clientStatisticRepository->find($id);

        if (empty($clientStatistic)) {
            Flash::error('Client Statistic not found');

            return redirect(route('clientStatistics.index'));
        }

        $clientStatistic = $this->clientStatisticRepository->update($request->all(), $id);

        Flash::success('Client Statistic updated successfully.');

        return redirect(route('clientStatistics.index'));
    }

    /**
     * Remove the specified ClientStatistic from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $clientStatistic = $this->clientStatisticRepository->find($id);

        if (empty($clientStatistic)) {
            Flash::error('Client Statistic not found');

            return redirect(route('clientStatistics.index'));
        }

        $this->clientStatisticRepository->delete($id);

        Flash::success('Client Statistic deleted successfully.');

        return redirect(route('clientStatistics.index'));
    }
}