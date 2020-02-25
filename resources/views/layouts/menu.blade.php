@if (Auth::user()->id == 1)
    <li class="{{ Request::is('users*') ? 'active' : '' }}">
        <a href="{!! route('users.index') !!}"><i class="fa fa-user"></i><span>Users</span></a>
    </li>
@endif

<li class="{{ Request::is('clients*') ? 'active' : '' }}">
    <a href="{{ route('clients.index') }}"><i class="fa fa-edit"></i><span>Clients</span></a>
</li>

<li class="{{ Request::is('clientStatistics*') ? 'active' : '' }}">
    <a href="{{ route('clientStatistics.index') }}"><i class="fa fa-edit"></i><span>Client Statistics</span></a>
</li>
