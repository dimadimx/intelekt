<div class="table-responsive-sm">
    <table class="table table-striped" id="clients-table">
        <thead>
            <th>User Id</th>
        <th>Api Uid</th>
        <th>Api Gid</th>
        <th>Api Belong Uid</th>
        <th>Login</th>
        <th>Phone</th>
        <th>Registration</th>
        <th>Warning</th>
            <th colspan="3">Action</th>
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
                        <a href="{{ route('clients.show', [$client->id]) }}" class='btn btn-ghost-success'><i class="fa fa-eye"></i></a>
                        <a href="{{ route('clients.edit', [$client->id]) }}" class='btn btn-ghost-info'><i class="fa fa-edit"></i></a>
                        {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-ghost-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>