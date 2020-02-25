<!-- Name Field -->
<div class="form-group">
    {!! Form::label('name', 'Name:') !!}
    <p>{{ $user->name }}</p>
</div>

<!-- Email Field -->
<div class="form-group">
    {!! Form::label('email', 'Email:') !!}
    <p>{{ $user->email }}</p>
</div>

<!-- Api User Field -->
<div class="form-group">
    {!! Form::label('api_user', 'Api User:') !!}
    <p>{{ $user->api_user }}</p>
</div>

<!-- Api Password Field -->
<div class="form-group">
    {!! Form::label('api_password', 'Api Password:') !!}
    <p>{{ $user->api_password }}</p>
</div>

<!-- Api Gid Field -->
<div class="form-group">
    {!! Form::label('api_gid', 'Api Gid:') !!}
    <p>{{ $user->api_gid }}</p>
</div>

<!-- Api Uid Field -->
<div class="form-group">
    {!! Form::label('api_uid', 'Api Uid:') !!}
    <p>{{ $user->api_uid }}</p>
</div>

<!-- Price Field -->
<div class="form-group">
    {!! Form::label('price', 'Price:') !!}
    <p>{{ $user->price }}</p>
</div>

