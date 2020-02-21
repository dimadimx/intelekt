<div class="table-responsive">
    <table class="table" id="clientStatistics-table">
        <thead>
            <tr>
                <th>Client Id</th>
        <th>Date</th>
        <th>Status</th>
                <th colspan="3">Action</th>
            </tr>
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
                        <a href="{{ route('clientStatistics.show', [$clientStatistic->id]) }}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                        <a href="{{ route('clientStatistics.edit', [$clientStatistic->id]) }}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                        {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
