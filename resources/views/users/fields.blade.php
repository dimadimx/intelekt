<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Email Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email', 'Email') !!}
    {!! Form::email('email', null, ['class' => 'form-control']) !!}
</div>

<!-- api_user Field -->
<div class="form-group col-sm-6">
    {!! Form::label('api_user', 'Api user') !!}
    {!! Form::text('api_user', null, ['class' => 'form-control']) !!}
</div>

<!-- api_password Field -->
<div class="form-group col-sm-6">
    {!! Form::label('api_password', 'Api password') !!}
    {!! Form::text('api_password', null, ['class' => 'form-control']) !!}
</div>

<!-- api_uid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('api_uid', 'Api uid') !!}
    {!! Form::text('api_uid', null, ['class' => 'form-control']) !!}
</div>

<!-- api_gid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('api_gid', 'Api gid') !!}
    {!! Form::text('api_gid', null, ['class' => 'form-control']) !!}
</div>

<!-- price Field -->
<div class="form-group col-sm-6">
    {!! Form::label('price', 'Price') !!}
    {!! Form::text('price', null, ['class' => 'form-control']) !!}
</div>
<!-- Password Field -->
<div class="form-group col-sm-6">
    {!! Form::label('password', 'Password') !!}
    {!! Form::password('password', ['class' => 'form-control']) !!}
</div>

<!-- Confirmation Password Field -->
<div class="form-group col-sm-6">
    {!! Form::label('password', 'Password Confirmation') !!}
    {!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('users.index') !!}" class="btn btn-default">Cancel</a>
</div>
