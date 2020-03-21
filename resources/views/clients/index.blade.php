@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Clients</h1>
        <h1 class="pull-right">
           <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{{ route('clients.sync') }}">Sync clients</a>
        </h1>
    </section>
    <div class="content">
        @if ($JobStatus)
            <div class="clearfix"></div>
            <div class="progress progress-xs active">
                <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="{{$JobStatus->progress_now}}" aria-valuemin="0" aria-valuemax="{{$JobStatus->progress_max}}" style="width: {{($JobStatus->progress_now * 100) /$JobStatus->progress_max}}%">
                    <span class="sr-only">{{$JobStatus->progress_now}}% Complete</span>
                </div>
            </div>
            {{ var_dump($JobStatus)}}
        @endif
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('clients.table')
            </div>
        </div>
        <div class="text-center">

        </div>
    </div>
@endsection

