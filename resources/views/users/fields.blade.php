<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Email Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email', 'Email:') !!}
    {!! Form::email('email', null, ['class' => 'form-control']) !!}
</div>

<!-- Password Field -->
<div class="form-group col-sm-6">
    {!! Form::label('password', 'Password:') !!}
    {!! Form::password('password', ['class' => 'form-control']) !!}
</div>

<!-- Confirmation Password Field -->
<div class="form-group col-sm-6">
    {!! Form::label('password', 'Password Confirmation') !!}
    {!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
</div>

<!-- Api User Field -->
<div class="form-group col-sm-6">
    {!! Form::label('api_user', 'Api User:') !!}
    {!! Form::text('api_user', null, ['class' => 'form-control']) !!}
</div>

<!-- Api Password Field -->
<div class="form-group col-sm-6">
    {!! Form::label('api_password', 'Api Password:') !!}
    {!! Form::text('api_password', null, ['class' => 'form-control']) !!}
</div>

<!-- Api Gid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('api_gid', 'Api Gid:') !!}
    {!! Form::number('api_gid', null, ['class' => 'form-control']) !!}
</div>

<!-- Api Uid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('api_uid', 'Api Uid:') !!}
    {!! Form::number('api_uid', null, ['class' => 'form-control']) !!}
</div>

<!-- Price Field -->
<div class="form-group col-sm-6">
    {!! Form::label('price', 'Price:') !!}
    {!! Form::number('price', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('users.index') }}" class="btn btn-default">Cancel</a>
</div>
