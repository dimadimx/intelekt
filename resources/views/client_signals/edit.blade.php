@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Client Signal
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($clientSignal, ['route' => ['clientSignals.update', $clientSignal->id], 'method' => 'patch']) !!}

                        @include('client_signals.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection