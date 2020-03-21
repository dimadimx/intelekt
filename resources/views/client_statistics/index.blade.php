@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Client Statistics</h1>
        <h1 class="pull-right">
           <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="#sync-sessions" data-toggle="modal">Sync sessions</a>
        </h1>
    </section>
    <div class="content">
        @if ($JobStatus and $JobStatus->status == 'executing')
            <div class="clearfix"></div>
            <div class="progress progress-xs active">
                <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="{{$JobStatus->progress_now}}" aria-valuemin="0" aria-valuemax="{{$JobStatus->progress_max}}" style="width: {{($JobStatus->progress_now * 100) /$JobStatus->progress_max}}%">
                    <span class="sr-only">{{$JobStatus->progress_now}}% Complete</span>
                </div>
            </div>
        @endif

        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('client_statistics.table')
            </div>
        </div>
        <div class="text-center">

        </div>
    </div>

    <div id="sync-sessions" class="modal fade">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                {!! Form::open(['route' => 'clientStatistics.syncSessions']) !!}
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                        <h4 class="modal-title">Sync sessions</h4>
                    </div>

                    <div class="modal-body">

                        <div class="row">

                            <!-- Date Field -->
                            <div class="form-group col-sm-12">
                                {!! Form::label('date', 'Date:') !!}
                                {!! Form::text('date', null, ['class' => 'form-control','id'=>'date']) !!}
                            </div>

                            @push('scripts')
                                <script type="text/javascript">
                                    $('#date').datetimepicker({
                                        format: 'YYYY-MM',
                                        useCurrent: false
                                    })
                                </script>
                            @endpush

                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Sync sessions</button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection

