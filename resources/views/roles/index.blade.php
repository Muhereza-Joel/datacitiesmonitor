@include('layouts.header')
@include('layouts.topBar')
@include('layouts.leftPane')

<main id="main" class="main">

    <div class="pagetitle mt-4">
        <div class="d-flex align-items-center justify-content-between">
            <div class="text-start">
                <h1 class="text-body-emphasis">Roles & Permissions Management</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                        <li class="breadcrumb-item active">Access Controls</li>
                    </ol>
                </nav>
            </div>
            <div>
                @can('create', \Spatie\Permission\Models\Role::class)
                <a href="{{ route('roles.create') }}" class="btn btn-primary btn-sm px-3 shadow-sm">
                    <i class="bi bi-shield-plus me-1"></i> Create New Role
                </a>
                @endcan
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <section class="section mt-4">
        <div class="row g-4">
            @forelse($roles as $role)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border shadow-sm bg-body text-body">
                    <div class="card-body pt-4 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-3 bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1.5 fs-5">
                                        <i class="bi bi-shield-lock"></i>
                                    </div>
                                    <h5 class="fw-bold text-body-emphasis mb-0 text-capitalize">
                                        {{ str_replace('-', ' ', $role->name) }}
                                    </h5>
                                </div>
                                <span class="badge bg-secondary-subtle text-secondary border rounded-pill font-monospace font-weight-normal" style="font-size: 0.7rem;">
                                    {{ $role->guard_name }}
                                </span>
                            </div>

                            <p class="text-secondary small mb-3">
                                Technical string pointer key identifier: <code class="text-danger small">{{ $role->name }}</code>
                            </p>

                            <div class="p-3 bg-body-tertiary rounded border mb-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="small fw-medium text-body">Authorized Abilities:</span>
                                    <span class="badge bg-primary rounded-pill shadow-sm">
                                        {{ $role->permissions->count() }} Managed Guard Elements
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="border-top pt-3 d-flex align-items-center justify-content-end gap-2">
                            @can('update', $role)
                            <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-success btn-sm px-3">
                                <i class="bi bi-pencil me-1"></i> Adjust Matrix
                            </a>
                            @endcan

                            @can('delete', $role)
                            <form action="{{ route('roles.destroy', $role->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to completely decommission this system role? Permissions will be unassigned from users.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endcan

                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center py-5 card border border-dashed shadow-sm">
                    <i class="bi bi-shield-slash text-muted display-3 d-block mb-3"></i>
                    <h5 class="fw-bold text-body-emphasis">No Custom Roles Configured</h5>
                    @can('create', \Spatie\Permission\Models\Role::class)
                    <a href="{{ route('roles.create') }}" class="btn btn-primary btn-sm px-4">
                        <i class="bi bi-shield-plus me-1"></i> Provision First Role
                    </a>
                    @endcan
                </div>
            </div>
            @endforelse
        </div>
    </section>

</main>

@include('layouts.footer')