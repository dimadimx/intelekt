@foreach($jobs as $job)
    <li><!-- Task item -->
        <a href="{{ route('JobController.index') }}">
            <h3>
                {{ @$job->output['title'] }}
                <small class="pull-right">{{ $job->progress_percentage }}%</small>
            </h3>
            <div class="progress xs">
                <div class="progress-bar progress-bar-aqua" style="width: {{ $job->progress_percentage }}%" role="progressbar" aria-valuenow="{{$job->progress_now}}" aria-valuemin="0" aria-valuemax="{{$job->progress_max}}">
                    <span class="sr-only">{{ $job->progress_percentage }}% Complete</span>
                </div>
            </div>
        </a>
    </li>
@endforeach
