@include('layouts.header')
@include('layouts.topBar')
@include('layouts.leftPane')

<style>
    /* Theme-aware variables */
    :root {
        --shield-primary: #4361ee;
        --shield-card-bg: rgba(255, 255, 255, 0.9);
        --shield-border: rgba(0, 0, 0, 0.08);
        --shield-row-hover: #f8f9ff;
    }

    [data-bs-theme="dark"] {
        --shield-card-bg: rgba(33, 37, 41, 0.9);
        --shield-border: rgba(255, 255, 255, 0.1);
        --shield-row-hover: #2b2f33;
    }

    .main {
        background-color: var(--bs-body-tertiary-bg);
        min-height: 100vh;
    }

    /* Modern Glass Card */
    .glass-card {
        background: var(--shield-card-bg);
        backdrop-filter: blur(10px);
        border: 1px solid var(--shield-border);
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    /* Search Input Styling */
    .search-wrapper {
        position: relative;
    }

    .search-wrapper i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--bs-secondary-color);
    }

    .search-wrapper .form-control {
        padding-left: 35px;
        border-radius: 8px;
        background-color: var(--bs-body-bg);
    }

    /* Accordion Customization */
    .accordion-item {
        border: none;
        margin-bottom: 1rem;
        border-radius: 12px !important;
        overflow: hidden;
        background: transparent;
    }

    .accordion-button {
        background-color: var(--bs-body-bg);
        color: var(--bs-body-color);
        font-weight: 600;
    }

    .accordion-button:not(.collapsed) {
        background-color: var(--bs-body-bg);
        color: var(--shield-primary);
        box-shadow: none;
        border-bottom: 1px solid var(--shield-border);
    }

    /* Permission Table Styling */
    .permission-table thead th {
        background-color: var(--bs-tertiary-bg);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 1px;
        color: var(--bs-secondary-color);
    }

    .permission-row:hover {
        background-color: var(--shield-row-hover);
    }

    .form-check-input:checked {
        background-color: #2ec4b6;
        border-color: #2ec4b6;
    }

    .badge-permission {
        font-size: 0.65rem;
        padding: 0.35em 0.65em;
        text-transform: uppercase;
    }

    /* Icon box for visual flair */
    .icon-box {
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

<main id="main" class="main">
    <div class="container-fluid">
        <form action="{{ route('roles.store') }}" method="POST" id="roleShieldForm">
            @csrf

            <div class="pagetitle py-4">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h1 class="h3 fw-bold text-body">Manage Roles And Permissions</h1>
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                                <li class="breadcrumb-item active">Roles and Permissions</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-md-6 text-md-end mt-3 mt-md-0">
                        <a href="{{ route('roles.index') }}" class="btn btn-link text-secondary text-decoration-none me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4 shadow-sm">
                            <i class="bi bi-shield-lock-fill me-2"></i>Save Role and Permissions
                        </button>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="glass-card sticky-top" style="top: 100px; z-index: 10;">
                        <div class="card-body p-4">
                            <h6 class="fw-bold mb-3 text-body">Role Name</h6>

                            <div class="mb-4">

                                <input type="text"
                                    class="form-control form-control-lg @error('name') is-invalid @enderror"
                                    name="name"
                                    value="{{ old('name') }}"
                                    placeholder="e.g. admin, content-manager"
                                    required>
                                <div class="form-text small mt-2">Use dashes for spaces (e.g. system-admin).</div>
                            </div>

                            <hr class="my-4 opacity-25">

                            <h6 class="fw-bold mb-3 text-body">Bulk Actions</h6>
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="toggleAllGlobal(true)">
                                    <i class="bi bi-check2-all me-1"></i> Select All Permissions
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="toggleAllGlobal(false)">
                                    <i class="bi bi-x-circle me-1"></i> Deselect All Permissions
                                </button>
                            </div>

                            <div class="mt-4 p-3 bg-body-tertiary rounded-3 border">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="small text-secondary">Permissions Selected:</span>
                                    <span class="badge bg-primary rounded-pill shadow-sm" id="global-counter">0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Matrix -->
                <div class="col-lg-8">
                    <div class="mb-3">
                        <div class="search-wrapper">
                            <i class="bi bi-search"></i>
                            <input type="text" id="modelSearch" class="form-control border shadow-sm" placeholder="Filter models by name...">
                        </div>
                    </div>

                    <div class="accordion" id="shieldAccordion">
                        @foreach($models as $index => $model)
                        @php $snakeModel = \Illuminate\Support\Str::snake($model); @endphp

                        <div class="accordion-item glass-card model-group" data-model-name="{{ strtolower($model) }}">
                            <div class="accordion-header d-flex align-items-center bg-transparent pe-3">
                                <button class="accordion-button {{ $index === 0 ? '' : 'collapsed' }} flex-grow-1"
                                    type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $snakeModel }}">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-box me-3 bg-primary-subtle text-primary rounded border border-primary-subtle">
                                            <i class="bi bi-layers"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-body">{{ preg_replace('/(?<!\ )[A-Z]/', ' $0', $model) }}</div>
                                            <div class="font-monospace text-secondary" style="font-size: 0.7rem;">App\Models\{{ $model }}</div>
                                        </div>
                                    </div>
                                </button>

                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge rounded-pill bg-body-secondary text-body-secondary border model-count-badge" style="font-size: 0.65rem;">0 Selected</span>
                                    <div class="btn-group btn-group-sm ms-2 shadow-sm">
                                        <button type="button" class="btn btn-light btn-sm border" title="All" onclick="toggleModelGroup('{{ $snakeModel }}', true)">
                                            <i class="bi bi-plus-lg text-success"></i>
                                        </button>
                                        <button type="button" class="btn btn-light btn-sm border" title="None" onclick="toggleModelGroup('{{ $snakeModel }}', false)">
                                            <i class="bi bi-dash text-danger"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div id="collapse-{{ $snakeModel }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" data-bs-parent="#shieldAccordion">
                                <div class="accordion-body p-0 border-top">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="ps-4" style="width: 40%">Capability</th>
                                                    <th style="width: 40%">Technical Key</th>
                                                    <th class="text-end pe-4" style="width: 20%">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($prefixes as $prefix)
                                                @php
                                                $permissionName = $prefix . '_' . $snakeModel;
                                                $badgeColor = match($prefix) {
                                                'view', 'view_any' => 'text-bg-info',
                                                'create' => 'text-bg-success',
                                                'update' => 'text-bg-warning',
                                                'delete', 'force_delete' => 'text-bg-danger',
                                                default => 'text-bg-secondary'
                                                };
                                                @endphp
                                                <tr class="permission-row">
                                                    <td class="ps-4">
                                                        <span class="badge {{ $badgeColor }} badge-permission mb-1 opacity-75">{{ str_replace('_', ' ', $prefix) }}</span>
                                                        <div class="small fw-medium text-body">Can {{ str_replace('_', ' ', $prefix) }} records</div>
                                                    </td>
                                                    <td><code class="small text-primary">{{ $permissionName }}</code></td>
                                                    <td class="text-end pe-4">
                                                        <div class="form-check form-switch d-inline-block">
                                                            <input class="form-check-input shield-checkbox"
                                                                type="checkbox"
                                                                name="permissions[]"
                                                                value="{{ $permissionName }}"
                                                                data-parent-model="{{ $snakeModel }}"
                                                                id="sw-{{ $permissionName }}">
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.shield-checkbox');
        const globalCounter = document.getElementById('global-counter');
        const searchInput = document.getElementById('modelSearch');

        function updateCounters() {
            const totalChecked = document.querySelectorAll('.shield-checkbox:checked').length;
            globalCounter.innerText = totalChecked;

            document.querySelectorAll('.model-group').forEach(group => {
                const modelId = group.querySelector('.accordion-collapse').id.replace('collapse-', '');
                const count = group.querySelectorAll('.shield-checkbox:checked').length;
                const badge = group.querySelector('.model-count-badge');

                badge.innerText = `${count} Selected`;
                if (count > 0) {
                    badge.classList.replace('bg-body-secondary', 'bg-primary');
                    badge.classList.replace('text-body-secondary', 'text-white');
                } else {
                    badge.classList.replace('bg-primary', 'bg-body-secondary');
                    badge.classList.replace('text-white', 'text-body-secondary');
                }
            });
        }

        checkboxes.forEach(cb => cb.addEventListener('change', updateCounters));

        window.toggleAllGlobal = function(state) {
            checkboxes.forEach(cb => cb.checked = state);
            updateCounters();
        };

        window.toggleModelGroup = function(modelSlug, state) {
            document.querySelectorAll(`.shield-checkbox[data-parent-model="${modelSlug}"]`)
                .forEach(cb => cb.checked = state);
            updateCounters();
        };

        searchInput.addEventListener('keyup', function() {
            const term = this.value.toLowerCase();
            document.querySelectorAll('.model-group').forEach(group => {
                const name = group.getAttribute('data-model-name');
                group.style.display = name.includes(term) ? 'block' : 'none';
            });
        });

        updateCounters();
    });
</script>

@include('layouts.footer')