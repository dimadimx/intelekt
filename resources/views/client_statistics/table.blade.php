<div class="table-responsive-sm">
    <table class="table table-striped" id="clientStatistics-table">
        <thead>
            <th>Client Id</th>
        <th>Date</th>
        <th>Status</th>
            <th colspan="3">Action</th>
        </thead>
        <tbody>
        @foreach($clientStatistics as $clientStatistic)
            <tr>
                <td>{{ $clientStatistic->client_id }}</td>
            <td>{{ $clientStatistic->date }}</td>
            <td>{{ $clientStatistic->status }}</td>
                <td>
                    {!! Form::open(['route' => ['clientStatistics.destroy', $clientStatistic->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('clientStatistics.show', [$clientStatistic->id]) }}" class='btn btn-ghost-success'><i class="fa fa-eye"></i></a>
                        <a href="{{ route('clientStatistics.edit', [$clientStatistic->id]) }}" class='btn btn-ghost-info'><i class="fa fa-edit"></i></a>
                        {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-ghost-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>