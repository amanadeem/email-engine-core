
@extends('layouts.app')

@section('content')
<div class="container-fluid px-5 py-3">

    <div class="row">
        @if(session()->has('error'))
            <div class="alert alert-danger">
                {{ session()->get('error') }}
            </div>
        @endif
        @if(session()->has('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
        @endif
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Users</div>
                <div class="card-body">
                    <button   class="btn btn-success add-user">
                        Add User
                    </button>
                    <table id="usersTable" class="table">
                        <thead>

                        </thead>
                        <tbody>

                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>





<!-- Modal-->
<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <form class="form" id="addUserForm" action="{{ route('users.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label>Name:</label>
                            <input type="text" name="name" class="form-control form-control-solid" required placeholder="Enter name" />
                        </div>
                        <div class="form-group">
                            <label>Email:</label>
                            <input type="email" name="email" class="form-control form-control-solid" required placeholder="Enter email" />
                        </div>
                        <div class="form-group">
                            <label>Phone:</label>
                            <input type="text" name="phone" class="form-control form-control-solid" required placeholder="Enter phone" />
                        </div>
                        <div class="form-group">
                            <label>Password:</label>
                            <input type="password" name="password" class="form-control form-control-solid" required placeholder="Enter password" />
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" id="addFormSubmit" class="btn btn-primary mr-2">Create</button>
                        <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
<!-- Edit user Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Edit user</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <form class="form" id="editUserForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label>Name:</label>
                            <input type="text" name="name" id="name" class="form-control form-control-solid" required placeholder="Enter name" />
                        </div>
                        <div class="form-group">
                            <label>Email:</label>
                            <input type="email" name="email" id="email" class="form-control form-control-solid" required placeholder="Enter email" />
                        </div>
                        <div class="form-group">
                            <label>Phone:</label>
                            <input type="text" name="phone" id="phone" class="form-control form-control-solid" required placeholder="Enter phone" />
                        </div>
                    </div>
                    <input type="hidden" name="user_id" id="user_id" />

                    <div class="card-footer">
                        <button type="submit" id="editFormSubmit" class="btn btn-primary mr-2">Update</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


{{-- <div class="modal fade" id="userDeleteModal" tabindex="-1" role="dialog" aria-labelledby="userDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userDeleteModalLabel">Delete user</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
               <p>Are you sure to delete this user ? </p>
            </div>
            <div class="modal-footer">
                <a id="deleteUrl" class="btn btn-success font-weight-bold">Yes</a>
                <button type="button" class="btn btn-dark font-weight-bold" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div> --}}

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteUserModalLabel">Delete User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this user?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteUserForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>



<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">


<script>
$(document).ready(function() {
    // $('select').selectpicker();
        // Handle form submission for adding a new user
    $('#submitBtn').click(function() {
        $('#userForm').submit();
    });


    var table = $('#usersTable').DataTable({
        // dom: 'Bfrtip',
        // responsive: true,
        paging: false,
        info: false,
        colReorder: true, // Enable ColReorder
        bPaginate: false,
        autoWidth: false,

        ajax: {
                url: "{{ route('users.index') }}"
            },
            columns: [
                {
                "data": "id",
                "title": "#",
                width: 10
                , render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: 'name'
                , title: 'Name'

            }, {
                data: 'email'
                , title: 'Email'


            },{
                data: 'phone'
                , title: 'Phone'

            },
            {
                data:"id",
                title: "Action",
                render : function(data,type,row){
                    // console.log(row);
                    let html = '<div class=""><a href="javascript:;" user_id='+ data +' class="col-2 text-center p-2 mx-2 edit-user btn-sm btn-success btn-icon" title="Edit details"><span class="svg-icon svg-icon-md"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none"fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><path d="M8,17.9148182 L8,5.96685884 C8,5.56391781 8.16211443,5.17792052 8.44982609,4.89581508 L10.965708,2.42895648 C11.5426798,1.86322723 12.4640974,1.85620921 13.0496196,2.41308426 L15.5337377,4.77566479 C15.8314604,5.0588212 16,5.45170806 16,5.86258077 L16,17.9148182 C16,18.7432453 15.3284271,19.4148182 14.5,19.4148182 L9.5,19.4148182 C8.67157288,19.4148182 8,18.7432453 8,17.9148182 Z" fill="#000000" fill-rule="nonzero" transform="translate(12.000000, 10.707409) rotate(-135.000000) translate(-12.000000, -10.707409) "></path><rect fill="#000000" opacity="0.3" x="5" y="20" width="15" height="2" rx="1"></rect></g></svg></span></a>'
                    html += '<a href="javascript:;" user_id='+ data +' class="col-2 text-center p-2 mx-2 delete-user btn-sm btn-success btn-icon" title="Delete Details"><span class="svg-icon svg-icon-md"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">        <rect x="0" y="0" width="24" height="24"></rect><path d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z" fill="#000000" fill-rule="nonzero"></path><path d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3"></path>    </g></svg>	    </span> </a>'

                        html += '</div>'
                    return html;
                }
            }
        ],

        dom: 'Bfrtip',


    });





    $(document).on('click', '.edit-user', function () {
        let user_id = $(this).attr('user_id');
        $.ajax({
            url: "{{ route('users.edit', ':id') }}".replace(':id', user_id),
            method: "GET",
            beforeSend: function () {
                $("#editUserForm").trigger('reset');
                $("#editUserForm").attr('action', '{{ route("users.update", ":id") }}'.replace(':id', user_id));
                $("#editUserModalLabel").html("Edit User");
            },
            success: function (data) {
                $("#editUserModal").modal('show');
                $("#name").val(data.name);
                $("#email").val(data.email);
                $("#phone").val(data.phone);
                $("#user_id").val(data.id);
            }
        });
    });


    // Corrected typo in the form submit
    // $('#editFormSubmit').click(function () {
    //     $('#editUserForm').submit(); // Changed 'edituserForm' to 'editUserForm'
    // });

    $(document).on('click', '.delete-user', function () {
        let user_id = $(this).attr('user_id');
        let deleteUrl = "{{ route('users.destroy', ':id') }}".replace(':id', user_id);

        $("#deleteUserForm").attr('action', deleteUrl);
        $("#deleteUserModal").modal('show');
    });

    $(document).on('click', '.add-user', function () {
        $("#addUserForm").attr('action', "{{ route('users.store') }}");
        $("#addUserModal").modal('show');
    });





});
</script>
@endsection
