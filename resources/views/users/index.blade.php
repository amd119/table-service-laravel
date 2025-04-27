@extends('layouts.app')

@section('title', 'Users')

@section('content-dashboard')
    @include('layouts.partials.sidebar')
    @include('layouts.partials.header')

    <main class="nxl-container" style="display:flex;flex-direction:column;min-height:97vh;">
        <div class="nxl-content">
            <!-- [ page-header ] start -->
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Users</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                        <li class="breadcrumb-item">Users</li>
                    </ul>
                </div>
                <div class="page-header-right ms-auto">
                    <div class="page-header-right-items">
                        <div class="d-flex d-md-none">
                            <a href="javascript:void(0)" class="page-header-right-close-toggle">
                                <i class="feather-arrow-left me-2"></i>
                                <span>Back</span>
                            </a>
                        </div>
                        <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                            <div class="dropdown">
                                <a class="btn btn-icon btn-light-brand" data-bs-toggle="dropdown" data-bs-offset="0, 10" data-bs-auto-close="outside">
                                    <i class="feather-paperclip"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="javascript:void(0);" class="dropdown-item">
                                        <i class="bi bi-filetype-pdf me-3"></i>
                                        <span>PDF</span>
                                    </a>
                                    <a href="javascript:void(0);" class="dropdown-item">
                                        <i class="bi bi-filetype-exe me-3"></i>
                                        <span>Excel</span>
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a href="javascript:void(0);" class="dropdown-item">
                                        <i class="bi bi-printer me-3"></i>
                                        <span>Print</span>
                                    </a>
                                </div>
                            </div>
                            <a href="{{ route('user.create') }}" class="btn btn-primary">
                                <i class="feather-plus me-2"></i>
                                <span>Create User</span>
                            </a>
                        </div>
                    </div>
                    <div class="d-md-none d-flex align-items-center">
                        <a href="javascript:void(0)" class="page-header-right-open-toggle">
                            <i class="feather-align-right fs-20"></i>
                        </a>
                    </div>
                </div>
            </div>
            <!-- [ page-header ] end -->
            <div class="notification-container px-3">
                @include('layouts.partials.notifications')
            </div>
            <!-- [ Main Content ] start -->
            <div class="main-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card stretch stretch-full">
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover" id="leadList">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Username</th>
                                                <th>Created At</th>
                                                <th>Role</th>
                                                <th class="text-end">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($data as $user)
                                                <tr class="single-item">
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $user->username }}</td>
                                                    <td>{{ $user->created_at }}</td>
                                                    <td>
                                                        @if ($user->role == 'administrator')
                                                            <span class="badge bg-soft-primary text-primary">Administrator</span>
                                                        @elseif ($user->role == 'waiter')
                                                            <span class="badge bg-soft-danger text-danger">Waiter</span>
                                                        @elseif ($user->role == 'kasir')
                                                            <span class="badge bg-soft-warning text-warning">Cashier</span>
                                                        @elseif ($user->role == 'owner')
                                                            <span class="badge bg-soft-info text-info">Owner</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="hstack justify-content-end">
                                                            <div class="dropdown">
                                                                <a href="javascript:void(0)" class="avatar-text avatar-md" data-bs-toggle="dropdown">
                                                                    <i class="feather feather-more-horizontal"></i>
                                                                </a>
                                                                <ul class="dropdown-menu">
                                                                    <li>
                                                                        <a href="{{ route('user.create') }}" class="dropdown-item d-flex d-md-none">
                                                                            <i class="feather feather-plus me-3"></i>
                                                                            <span>Create</span>
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a class="dropdown-item edit-user" href="javascript:void(0);" data-id="{{ $user->iduser }}">
                                                                            <i class="feather feather-edit-3 me-3"></i>
                                                                            <span>Edit</span>
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a class="dropdown-item delete-user" href="javascript:void(0);" data-id="{{ $user->iduser }}" >
                                                                            <i class="feather feather-trash-2 me-3"></i>
                                                                            <span>Delete</span>
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
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
                </div>
            </div>
            <!-- [ Main Content ] end -->
        </div>
        <!-- [ Footer ] start -->
        @include('layouts.partials.footer')
        <!-- [ Footer ] end -->
    </main>

    <!-- [ Edit User Modal ] start -->
    @foreach($data as $user)
        <form action="{{ route('user.update', $user->iduser) }}" method="POST" enctype="multipart/form-data" id="editForm-{{ $user->iduser }}">
            @csrf
            @method('PUT')
            <div class="modal fade" id="editusermodal-{{ $user->iduser }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTitleId">Edit User</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="notes-box">
                                <div class="notes-content">
                                    <div class="row">
                                        <div class="col-md-12 mb-2">
                                            <label class="form-label">Username: </label>
                                            <div class="input-group">
                                                <div class="input-group-text"><i class="feather-user"></i></div>
                                                <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username', $user->username) }}" placeholder="Username">
                                            </div>
                                            @error('username')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <label class="form-label">Password: </label>
                                            <div class="input-group">
                                                <div class="input-group-text"><i class="feather-lock"></i></div>
                                                <input type="password" class="form-control no-arrows @error('password') is-invalid @enderror" id="password" name="password" value="" placeholder="Password">
                                            </div>
                                            @error('password')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Role: </label>
                                            <select class="form-control @error('role') is-invalid @enderror" name="role" id="role-{{ $user->iduser }}" data-select2-selector="role">
                                                <option value="administrator" {{ $user->role == 'administrator' ? 'selected' : '' }} data-bg="bg-primary">Administrator</option>
                                                <option value="waiter" {{ $user->role == 'waiter' ? 'selected' : '' }} data-bg="bg-danger">Waiter</option>
                                                <option value="kasir" {{ $user->role == 'kasir' ? 'selected' : '' }} data-bg="bg-warning">Cashier</option>
                                                <option value="owner" {{ $user->role == 'owner' ? 'selected' : '' }} data-bg="bg-info">Owner</option>
                                            </select>
                                            @error('role')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <!-- Original button will be replaced by JavaScript with Cancel and Clear Form buttons -->
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="float-left btn btn-success">Save Changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @endforeach
    <!-- [ Edit User Modal ] end -->

    <!-- [ Delete User Form ] start -->
    <form id="deleteUserForm" style="display:none;" method="POST" action="{{ route('user.delete') }}">
        @csrf
        @method('DELETE')
        <input type="hidden" name="iduser" id="delete_iduser">
    </form>
    <!-- [ Delete User Form ] end -->

@endsection
