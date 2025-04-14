@extends('layout.treeLayout')

@section('content')

    <link rel="stylesheet" href="{{ asset('tree/list.css') }}">

    <div id="main" class="main">
        <div class="modal fade" id="dataModal" tabindex="-1" aria-labelledby="dataModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="dataModalLabel"></h5>
                    </div>
                    <div id="topNotification" class="notification"></div>
                    <div class="modal-body">

                        <div id="modalContent">

                            <!-- Dynamic content will be inserted here -->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            @if (session('success'))
                <div id="successMessage" class="alert alert-success">
                    {{ session('success') }}
                </div>
                <script>
                    setTimeout(function() {
                        document.getElementById('successMessage').style.display = 'none';
                    }, 1000);
                </script>
            @endif

            @if ($parents->isEmpty() && !request('search'))
                <div class="alert alert-info container p-2 text-center">
                    No data available.
                </div>
                <div class="add d-flex justify-content-center">
                    <a href="{{ route('form') }}" class="btn btn-primary ">Add New Family</a>
                </div>
            @else
                <div class="row mb-3 align-items-center">
                    <div class="col-md-4 d-flex align-items-center">
                        <label for="perPage" class="mb-0 me-2">Show</label>
                        <select id="perPage" class="form-select w-auto" aria-label="Records per page">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                            <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex">
                            <form method="GET" action="{{ route('list') }}" class="d-flex w-100">
                                <input class="form-control me-2 flex-grow-1" type="search" placeholder="Search"
                                    name="search" value="{{ request('search') }}" aria-label="Search">
                                <button class="btn btn-outline-primary" type="submit">Search</button>
                                <a href="{{ route('list') }}" class="btn btn-outline-primary ms-2">Reset</a>
                            </form>
                        </div>
                    </div>

                    <div class="col-md-4 d-flex justify-content-end">
                        <a href="{{ route('form') }}" class="btn btn-primary plus-btn w-75">Add New Family</a>
                    </div>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Gender</th>
                            <th>Status</th>
                            {{-- <th>Relation</th> --}}
                            <th>Mobile No</th>
                            <th>Address</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($parents->isEmpty())
                            <tr>
                                <td colspan="8" class="text-center">No Data Found</td>
                            </tr>
                        @else
                            @foreach ($parents as $parent)
                                <tr>
                                    <td>{{ $parent->name }}</td>
                                    <td>{{ $parent->email }}</td>
                                    <td>{{ $parent->gender }}</td>
                                    <td>{{ $parent->status }}</td>
                                    {{-- <td>{{ $parent->relation }}</td> --}}
                                    <td>{{ $parent->phone }}</td>
                                    <td>{{ $parent->address }}</td>
                                    <td>
                                        <button id="three-dot-icon" style="border:none">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <div class="action">
                                            <ul>
                                                <li>
                                                    <a href="javascript:void(0)" class="view-record"
                                                        data-name="{{ $parent->name }}" data-id="{{ $parent->id }}">
                                                        <i class="ri-list-check me-2"></i>List
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('edit', ['id' => $parent->id]) }}"
                                                        data-name="{{ $parent->name }}" data-id="{{ $parent->id }}"
                                                        class="edit-parent">
                                                        <i class="ri-edit-box-line me-2"></i>Edit
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('delete', ['id' => $parent->id]) }}"
                                                        data-name="{{ $parent->name }}"
                                                        data-has-children="{{ $parent->children->isNotEmpty() }}"
                                                        class="delete-parent">
                                                        <i class="ri-delete-bin-2-line me-2"></i>Delete
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('listadd', ['id' => $parent->id]) }}">
                                                        <i class="ri-user-add-line me-2"></i>Add Member
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('view', ['id' => $parent->id]) }}"><i
                                                            class="ri-node-tree me-2"></i>Tree</a>

                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>

                @if ($parents->isNotEmpty())
                    <div class="pagination">
                        {{ $parents->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
                    </div>
                @endif
            @endif
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $(document).on('click', '#three-dot-icon', function(e) {
                e.stopPropagation();
                const actions = $(this).next('.action');
                $('.action').not(actions).hide();
                actions.toggle();
            });

            $(document).click(function(e) {
                if (!$(e.target).closest('.action, #three-dot-icon').length) {
                    $('.action').hide();
                }
            });
        });

        ///////////////////////////*** View Record in Modal ***///////////////////////////

        let currentPath = '';

        $(document).on('click', '.view-record', function(e) {
            e.preventDefault();
            $('.action').hide();

            const recordName = $(this).data('name');
            const parentId = $(this).data('id');

            $.ajax({
                url: '/get-children/' + parentId,
                method: 'GET',
                success: function(childResponse) {
                    let childRecords = childResponse.children;
                    const wife = childResponse.wife;

                    let modalContent = '';

                    if (childRecords && childRecords.length > 0) {
                        if (currentPath === '') {
                            currentPath = recordName;
                        } else {
                            currentPath += ' -> ' + recordName;
                        }

                        $('#dataModalLabel').text(currentPath);

                        modalContent += `
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Gender</th>
                                <th scope="col">Status</th>
                                <th scope="col">Relation</th>
                                <th scope="col">Phone</th>
                                <th scope="col">Address</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

                        childRecords.forEach(child => {
                            let relation = '';
                            if (wife && wife.id === child.id) {
                                relation = 'wife';
                            } else if (child.gender === 'Female') {
                                relation = 'Daughter';
                            } else {
                                relation = child.relation;
                            }

                            let listIcon = '';
                            let treeIcon = '';

                            if (child.relation !== 'wife' && child.status !== 'Unmarried' && !(
                                    child.gender === 'Female' && child.relation === 'Daughter'
                                )) {
                                listIcon =
                                    `<li><a href="javascript:void(0)" class="view-record" data-id="${child.id}" data-name="${child.name}"><i class="ri-list-check me-2"></i>List</a></li>`;
                            }

                            if (child.status === 'Married' && child.gender === 'Male') {
                                let treeUrl = `list/tree-list/view/${child.id}`;
                                treeIcon =
                                    `<li><a href="${treeUrl}"><i class="ri-node-tree me-2"></i>Tree</a></li>`;
                            }

                            modalContent += `
                        <tr>
                            <td>${child.name}</td>
                            <td>${child.email}</td>
                            <td>${child.gender}</td>
                            <td>${child.status}</td>
                            <td>${relation}</td>
                            <td>${child.phone}</td>
                            <td>${child.address}</td>
                            <td>
                                <button id="three-dot-icon" style="border:none"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                <div class="action">
                                    <ul>
                                        ${listIcon}
                                        ${treeIcon}
                                        <li><a href="javascript:void(0)" class="edit-child" data-id="${child.id}" data-name="${child.name}"><i class="ri-edit-box-line me-2"></i>Edit</a></li>
                                        <li><a href="javascript:void(0)" class="delete-child" data-id="${child.id}" data-name="${child.name}"><i class="ri-delete-bin-2-line me-2"></i>Delete</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    `;
                        });

                        modalContent += `
                        </tbody>
                    </table>
                `;
                        $('#modalContent').html(modalContent);
                        $('#dataModal').modal('show');
                    } else {
                        showNotification("No Child available", "error");
                    }
                },
                error: function() {
                    alert("Error loading children data.");
                }
            });
        });

        $('#dataModal').on('hidden.bs.modal', function() {
            currentPath = '';
        });

        function showNotification(message, type) {
            let notification = $('#topNotification');
            notification.text(message).removeClass('success error').addClass(type).fadeIn();
            setTimeout(() => {
                notification.fadeOut();
            }, 3000);
        }

        ///////////////////////////*** Edit Child ***///////////////////////////

        $(document).on('click', '.edit-child', function(e) {
            e.preventDefault();
            const childName = $(this).data('name');
            const childId = $(this).data('id');
            const editUrl = `{{ route('childedit', ['id' => ':id']) }}`.replace(':id', childId);
            Swal.fire({
                title: `Are you sure you want to edit "${childName}"?`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, edit it!",
                cancelButtonText: "Cancel"
            }).then((result) => {
                if (result.isConfirmed) {

                    window.location.href = editUrl;
                }
            });
        });

        ///////////////////////////*** Edit Parent ***///////////////////////////

        $(document).on('click', '.edit-parent', function(e) {
            e.preventDefault();
            const name = $(this).data('name');
            const parentId = $(this).data('id');
            const editUrl = `{{ route('edit', ['id' => ':id']) }}`.replace(':id', parentId);
            Swal.fire({
                title: `Are you sure you want to edit "${name}"?`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, edit it!",
                cancelButtonText: "Cancel"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = editUrl;
                }
            });
        });

        ///////////////////////////*** Delete Child ***///////////////////////////

        $(document).on('click', '.delete-child', function(e) {
            e.preventDefault();

            const childName = $(this).data('name');
            const childId = $(this).data('id');
            const row = $(this).closest('tr');

            $.ajax({
                url: '/child-check/' + childId,
                method: 'GET',
                success: function(response) {
                    if (response.status === 'error' && response.has_children) {
                        Swal.fire({
                            title: `Cannot delete "${childName}".`,
                            text: response.message,
                            icon: 'warning',
                            confirmButtonColor: '#3085d6',
                        });
                    } else if (response.status === 'success' && !response.has_children) {
                        Swal.fire({
                            title: `Are you sure you want to delete "${childName}"?`,
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#d33",
                            cancelButtonColor: "#3085d6",
                            confirmButtonText: "Yes, delete it!",
                            cancelButtonText: "Cancel",
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    url: '/child-delete/' + childId,
                                    method: 'GET',
                                    success: function() {
                                        Swal.fire({
                                            title: `"${childName}" deleted successfully.`,
                                            icon: 'success',
                                            confirmButtonColor: '#3085d6',
                                        });

                                        row.remove();
                                        if ($('#modalContent tbody tr').length ===
                                            0) {
                                            $('#dataModal').modal('hide');
                                        }
                                    },
                                    error: function() {
                                        Swal.fire({
                                            title: 'Error',
                                            text: 'Something went wrong. Please try again later.',
                                            icon: 'error',
                                            confirmButtonColor: '#3085d6',
                                        });
                                    },
                                });
                            }
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: response.message,
                            icon: 'error',
                            confirmButtonColor: '#3085d6',
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        title: 'Error',
                        text: 'Unable to check for associated records. Please try again later.',
                        icon: 'error',
                        confirmButtonColor: '#3085d6',
                    });
                },
            });
        });


        ///////////////////////////*** Delete Parent ***///////////////////////////

        $(document).on('click', '.delete-parent', function(e) {
            e.preventDefault();

            const parentName = $(this).data('name');
            const hasChildren = $(this).data('has-children');
            const deleteUrl = $(this).attr('href');
            const row = $(this).closest('tr');

            if (hasChildren) {
                Swal.fire({
                    title: `Cannot delete "${parentName}"`,
                    text: "This record has associated child records. Please remove the child records first.",
                    icon: "warning",
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "OK"
                });
            } else {
                Swal.fire({
                    title: `Are you sure you want to delete "${parentName}"?`,

                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, delete it!",
                    cancelButtonText: "Cancel"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: deleteUrl,
                            method: 'GET',
                            success: function() {
                                Swal.fire({
                                    title: `"${parentName}" deleted successfully.`,
                                    icon: 'success',
                                    confirmButtonColor: '#3085d6'
                                });
                                row.remove();
                                if ($('#modalContent').children().length === 0) {
                                    location.reload();
                                }
                            },
                            error: function() {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'There was an issue deleting the record.',
                                    icon: 'error',
                                    confirmButtonColor: '#3085d6'
                                });
                            }
                        });
                    }
                });
            }
        });


        $(document).ready(function() {
            $('#perPage').change(function() {
                const perPage = $(this).val();
                const url = new URL(window.location.href);
                url.searchParams.set('per_page', perPage);
                window.location.href = url.toString();
            });
        });
    </script>

@endsection
