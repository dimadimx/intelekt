@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Client Statistic
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($clientStatistic, ['route' => ['clientStatistics.update', $clientStatistic->id], 'method' => 'patch']) !!}

                        @include('client_statistics.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection