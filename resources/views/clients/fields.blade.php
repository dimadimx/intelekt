<!-- User Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('user_id', 'User Id:') !!}
    {!! Form::number('user_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Api Uid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('api_uid', 'Api Uid:') !!}
    {!! Form::number('api_uid', null, ['class' => 'form-control']) !!}
</div>

<!-- Api Gid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('api_gid', 'Api Gid:') !!}
    {!! Form::number('api_gid', null, ['class' => 'form-control']) !!}
</div>

<!-- Api Belong Uid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('api_belong_uid', 'Api Belong Uid:') !!}
    {!! Form::number('api_belong_uid', null, ['class' => 'form-control']) !!}
</div>

<!-- Login Field -->
<div class="form-group col-sm-6">
    {!! Form::label('login', 'Login:') !!}
    {!! Form::text('login', null, ['class' => 'form-control']) !!}
</div>

<!-- Phone Field -->
<div class="form-group col-sm-6">
    {!! Form::label('phone', 'Phone:') !!}
    {!! Form::text('phone', null, ['class' => 'form-control']) !!}
</div>

<!-- Registration Field -->
<div class="form-group col-sm-6">
    {!! Form::label('registration', 'Registration:') !!}
    {!! Form::text('registration', null, ['class' => 'form-control','id'=>'registration']) !!}
</div>

@push('scripts')
    <script type="text/javascript">
        $('#registration').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: false
        })
    </script>
@endpush

<!-- Warning Field -->
<div class="form-group col-sm-6">
    {!! Form::label('warning', 'Warning:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('warning', 0) !!}
        {!! Form::checkbox('warning', '1', null) !!}
    </label>
</div>


<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('clients.index') }}" class="btn btn-default">Cancel</a>
</div>
