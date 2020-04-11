<?php

namespace App\Http\Controllers;

use App\DataTables\ClientSignalDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateClientSignalRequest;
use App\Http\Requests\UpdateClientSignalRequest;
use App\Jobs\Telegram;
use App\Repositories\ClientSignalRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use Response;

class ClientSignalController extends AppBaseController
{
    /** @var  ClientSignalRepository */
    private $clientSignalRepository;

    public function __construct(ClientSignalRepository $clientSignalRepo)
    {
        $this->clientSignalRepository = $clientSignalRepo;
    }

    /**
     * Display a listing of the ClientSignal.
     *
     * @param ClientSignalDataTable $clientSignalDataTable
     * @return Response
     */
    public function index(ClientSignalDataTable $clientSignalDataTable)
    {
        return $clientSignalDataTable->render('client_signals.index');
    }

    /**
     * Show the form for creating a new ClientSignal.
     *
     * @return Response
     */
    public function create()
    {
        return view('client_signals.create');
    }

    /**
     * Store a newly created ClientSignal in storage.
     *
     * @param CreateClientSignalRequest $request
     *
     * @return Response
     */
    public function store(CreateClientSignalRequest $request)
    {
        $input = $request->all();

        $clientSignal = $this->clientSignalRepository->create($input);

        Flash::success('Client Signal saved successfully.');

        return redirect(route('clientSignals.index'));
    }

    /**
     * Display the specified ClientSignal.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $clientSignal = $this->clientSignalRepository->find($id);

        if (empty($clientSignal)) {
            Flash::error('Client Signal not found');

            return redirect(route('clientSignals.index'));
        }

        return view('client_signals.show')->with('clientSignal', $clientSignal);
    }

    /**
     * Show the form for editing the specified ClientSignal.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $clientSignal = $this->clientSignalRepository->find($id);

        if (empty($clientSignal)) {
            Flash::error('Client Signal not found');

            return redirect(route('clientSignals.index'));
        }

        return view('client_signals.edit')->with('clientSignal', $clientSignal);
    }

    /**
     * Update the specified ClientSignal in storage.
     *
     * @param  int              $id
     * @param UpdateClientSignalRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateClientSignalRequest $request)
    {
        $clientSignal = $this->clientSignalRepository->find($id);

        if (empty($clientSignal)) {
            Flash::error('Client Signal not found');

            return redirect(route('clientSignals.index'));
        }

        $clientSignal = $this->clientSignalRepository->update($request->all(), $id);

        Flash::success('Client Signal updated successfully.');

        return redirect(route('clientSignals.index'));
    }

    /**
     * Remove the specified ClientSignal from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $clientSignal = $this->clientSignalRepository->find($id);

        if (empty($clientSignal)) {
            Flash::error('Client Signal not found');

            return redirect(route('clientSignals.index'));
        }

        $this->clientSignalRepository->delete($id);

        Flash::success('Client Signal deleted successfully.');

        return redirect(route('clientSignals.index'));
    }

    /**
     * sync clients in storage.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function updateSignals()
    {
        Telegram::dispatchNow(Auth::user());

        sleep(1);
        return redirect(route('clientSignals.index'));
    }
}
