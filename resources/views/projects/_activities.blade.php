<div class="core-activities">
    {{-- TODO: CLEAN THIS! --}}
    @foreach ($project->activities as $activity)
        <p class="text-secondary m-0">
            @if ($activity->description == 'updated_project' && count($activity->changes['after']) == 1)
                <span>
                    <span class="text-info">{{ $activity->user->name }}</span> updated the {{ key($activity->changes['after']) }} of the project,
                    <span class="text-danger">from</span>
                    "{{ $activity->changes['before'][key($activity->changes['before'])] }}"
                    <span class='text-success'>to</span>
                    "{{ $activity->changes['after'][key($activity->changes['after'])]}}"
                </span>
            @elseif ($activity->description == 'updated_project' && count($activity->changes['after']) > 1)
                <span>
                    <span class="text-info">{{ $activity->user->name }}</span> updated the project
                </span>
            @else 
                <span class="{{ $project->activitiesDiffForHumans($activity->description . '_color') }}">
                    <span class="text-info">{{ $activity->user->name }}</span> {{ $project->activitiesDiffForHumans($activity->description) }} {{ ! in_array($activity->description, ['created_project', 'updated_project']) ? "\"{$activity->subject->body}\"" : '' }}
                </span>
            @endif
            <span class="text-secondary">
                - {{ $activity->created_at->diffForHumans(null, true, true) }}
            </span>
        </p>
    @endforeach
</div>