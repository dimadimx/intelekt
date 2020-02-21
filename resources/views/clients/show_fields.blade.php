<!-- User Id Field -->
<div class="form-group">
    {!! Form::label('user_id', 'User Id:') !!}
    <p>{{ $client->user_id }}</p>
</div>

<!-- Api Uid Field -->
<div class="form-group">
    {!! Form::label('api_uid', 'Api Uid:') !!}
    <p>{{ $client->api_uid }}</p>
</div>

<!-- Api Gid Field -->
<div class="form-group">
    {!! Form::label('api_gid', 'Api Gid:') !!}
    <p>{{ $client->api_gid }}</p>
</div>

<!-- Api Belong Uid Field -->
<div class="form-group">
    {!! Form::label('api_belong_uid', 'Api Belong Uid:') !!}
    <p>{{ $client->api_belong_uid }}</p>
</div>

<!-- Login Field -->
<div class="form-group">
    {!! Form::label('login', 'Login:') !!}
    <p>{{ $client->login }}</p>
</div>

<!-- Phone Field -->
<div class="form-group">
    {!! Form::label('phone', 'Phone:') !!}
    <p>{{ $client->phone }}</p>
</div>

<!-- Registration Field -->
<div class="form-group">
    {!! Form::label('registration', 'Registration:') !!}
    <p>{{ $client->registration }}</p>
</div>

<!-- Warning Field -->
<div class="form-group">
    {!! Form::label('warning', 'Warning:') !!}
    <p>{{ $client->warning }}</p>
</div>

