@extends('layouts.index')
@section('title', 'Manage Users')

@section('subheader')
    @component('layouts.partials._subheader.subheader-v1')
        @slot('title')
            Manage Users
        @endslot
        @slot('action')
            <button type="button" class="btn btn-primary font-weight-bolder" data-toggle="modal" data-target="#userModal"
                onclick="resetForm()">
                <i class="flaticon-plus"></i> New User
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
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @foreach ($user->roles as $role)
                                        <span
                                            class="label label-lg label-light-primary label-inline">{{ $role->name }}</span>
                                    @endforeach
                                </td>
                                <td>{{ $user->created_at->format('d M Y') }}</td>
                                <td nowrap="nowrap">
                                    <button class="btn btn-sm btn-clean btn-icon mr-2"
                                        onclick="editUser({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}', '{{ $user->roles->first() ? $user->roles->first()->name : '' }}')">
                                        <i class="flaticon2-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-clean btn-icon" onclick="deleteUser({{ $user->id }})">
                                        <i class="flaticon2-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">New User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form id="userForm">
                    @csrf
                    <input type="hidden" id="method" name="_method" value="POST">
                    <input type="hidden" id="user_id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" name="name" id="name" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" name="email" id="email" required>
                        </div>
                        <div class="form-group">
                            <label>Role</label>
                            <select class="form-control" name="role" id="role" required>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Password <span class="text-muted small">(Leave blank to keep current)</span></label>
                            <input type="password" class="form-control" name="password" id="password">
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
            document.getElementById('userForm').reset();
            document.getElementById('method').value = 'POST';
            document.getElementById('user_id').value = '';
            document.getElementById('modalTitle').innerText = 'New User';
        }

        function editUser(id, name, email, role) {
            document.getElementById('user_id').value = id;
            document.getElementById('name').value = name;
            document.getElementById('email').value = email;
            document.getElementById('role').value = role;
            document.getElementById('method').value = 'PUT';
            document.getElementById('modalTitle').innerText = 'Edit User';
            $('#userModal').modal('show');
        }

        document.getElementById('userForm').addEventListener('submit', function(e) {
            e.preventDefault();
            let id = document.getElementById('user_id').value;
            let method = document.getElementById('method').value;
            let url = method === 'POST' ? "{{ route('admin.users.store') }}" : "/admin/users/" + id;

            // Prepare data
            let formData = new FormData(this);
            if (method === 'PUT') {
                formData.append('_method', 'PUT');
            }

            fetch(url, {
                    method: 'POST', // Always POST for FormData with method override
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
                })
                .catch(err => console.error(err));
        });

        function deleteUser(id) {
            if (confirm('Are you sure?')) {
                fetch("/admin/users/" + id, {
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
