
<li class="nav-item {{ Request::is('users*') ? 'active' : '' }}">
    <a class="nav-link" href="{!! route('users.index') !!}">
        <i class="nav-icon icon-cursor"></i>
        <span>Users</span>
    </a>
</li>


<li class="nav-item {{ Request::is('clients*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('clients.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Clients</span>
    </a>
</li>
<li class="nav-item {{ Request::is('clientStatistics*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('clientStatistics.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Client Statistics</span>
    </a>
</li>
