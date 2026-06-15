@props(['indicator', 'projects'])

<div class="card p-3 mb-2">
    <div class="card-title py-1">
        Attach To Project
    </div>
    <div class="card-body p-0">
        <form action="{{ route('indicators.attach-project', $indicator->id) }}" id="attach-project-form" method="POST">
            @csrf
            @method('PATCH')
            <div class="mb-3">
                <label for="project_id" class="form-label">Select Project</label>
                <select class="form-select form-control" id="project_id" name="project_id" required>
                    <option value="">-- Select a Project --</option>
                    @foreach($projects as $project)
                    <option value="{{ $project->id }}" {{ $indicator->project_id == $project->id ? 'selected' : '' }}>
                        {{ $project->title ?? $project->name }}
                    </option>
                    @endforeach
                </select>
                <div class="invalid-feedback">Please select a project.</div>
            </div>

            <button type="submit" id="btn-attach-project" class="btn btn-primary btn-sm">
                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                Save Project
            </button>
        </form>
    </div>
</div>