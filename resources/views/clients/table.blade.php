<div class="table-responsive">
    <table class="table" id="clients-table">
        <thead>
            <tr>
                <th>User Id</th>
        <th>Api Uid</th>
        <th>Api Gid</th>
        <th>Api Belong Uid</th>
        <th>Login</th>
        <th>Phone</th>
        <th>Registration</th>
        <th>Warning</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($clients as $client)
            <tr>
                <td>{{ $client->user_id }}</td>
            <td>{{ $client->api_uid }}</td>
            <td>{{ $client->api_gid }}</td>
            <td>{{ $client->api_belong_uid }}</td>
            <td>{{ $client->login }}</td>
            <td>{{ $client->phone }}</td>
            <td>{{ $client->registration }}</td>
            <td>{{ $client->warning }}</td>
                <td>
                    {!! Form::open(['route' => ['clients.destroy', $client->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('clients.show', [$client->id]) }}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                        <a href="{{ route('clients.edit', [$client->id]) }}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                        {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
