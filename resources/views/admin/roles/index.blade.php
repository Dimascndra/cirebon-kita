@extends('layouts.index')
@section('title', 'Manage Roles')

@section('subheader')
    @component('layouts.partials._subheader.subheader-v1')
        @slot('title')
            Manage Roles
        @endslot
        @slot('action')
            <button type="button" class="btn btn-primary font-weight-bolder" data-toggle="modal" data-target="#roleModal"
                onclick="resetForm()">
                <i class="flaticon-plus"></i> New Role
            </button>
        @endslot
    @endcomponent
@endsection

@section('content')
    <div class="card card-custom">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-checkable" id="kt_datatable"
                    style="margin-top: 13px !important">
                    <thead>
                        <tr>
                            <th>Role Name</th>
                            <th>User Count</th>
                            <th>Permissions</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $role)
                            <tr>
                                <td><span class="font-weight-bold">{{ $role->name }}</span></td>
                                <td>{{ $role->users_count }}</td>
                                <td>
                                    @foreach ($role->permissions->take(5) as $perm)
                                        <span
                                            class="label label-sm label-light-success label-inline mr-1">{{ $perm->name }}</span>
                                    @endforeach
                                    @if ($role->permissions->count() > 5)
                                        <span
                                            class="label label-sm label-light-dark label-inline">+{{ $role->permissions->count() - 5 }}
                                            more</span>
                                    @endif
                                </td>
                                <td nowrap="nowrap">
                                    <button class="btn btn-sm btn-clean btn-icon mr-2"
                                        onclick="editRole({{ $role->id }}, '{{ $role->name }}', {{ $role->permissions->pluck('name') }})">
                                        <i class="flaticon2-pencil"></i>
                                    </button>
                                    @if (!in_array($role->name, ['SuperAdmin']))
                                        <button class="btn btn-sm btn-clean btn-icon"
                                            onclick="deleteRole({{ $role->id }})">
                                            <i class="flaticon2-trash"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="roleModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">New Role</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form id="roleForm">
                    @csrf
                    <input type="hidden" id="method" name="_method" value="POST">
                    <input type="hidden" id="role_id">
                    <div class="modal-body">
                        <div class="form-group mb-8">
                            <label>Role Name</label>
                            <input type="text" class="form-control" name="name" id="name" required>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Permissions</label>
                            <div class="row" id="permission-list">
                                @foreach ($permissions as $perm)
                                    <div class="col-md-4 mb-2">
                                        <label class="checkbox">
                                            <input type="checkbox" name="permissions[]" value="{{ $perm->name }}"
                                                class="perm-check" />
                                            <span></span>
                                            {{ $perm->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary font-weight-bold"
                            data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary font-weight-bold" id="saveBtn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function resetForm() {
            document.getElementById('roleForm').reset();
            document.getElementById('method').value = 'POST';
            document.getElementById('role_id').value = '';
            document.getElementById('modalTitle').innerText = 'New Role';
            // Uncheck all permissions
            document.querySelectorAll('.perm-check').forEach(cb => cb.checked = false);
        }

        function editRole(id, name, permissions) {
            document.getElementById('role_id').value = id;
            document.getElementById('name').value = name;
            document.getElementById('method').value = 'PUT';
            document.getElementById('modalTitle').innerText = 'Edit Role';

            // Reset checks
            document.querySelectorAll('.perm-check').forEach(cb => cb.checked = false);

            // Check active permissions
            permissions.forEach(p => {
                let cb = document.querySelector(`.perm-check[value="${p}"]`);
                if (cb) cb.checked = true;
            });

            $('#roleModal').modal('show');
        }

        document.getElementById('roleForm').addEventListener('submit', function(e) {
            e.preventDefault();
            let id = document.getElementById('role_id').value;
            let method = document.getElementById('method').value;
            let url = method === 'POST' ? "{{ route('admin.roles.store') }}" : "/admin/roles/" + id;

            let formData = new FormData(this);
            if (method === 'PUT') formData.append('_method', 'PUT');

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + JSON.stringify(data.errors));
                    }
                });
        });

        function deleteRole(id) {
            if (confirm('Are you sure?')) {
                fetch("/admin/roles/" + id, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) location.reload();
                    });
            }
        }
    </script>
@endsection
