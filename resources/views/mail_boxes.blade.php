
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
                <div class="card-header">Mail Boxes</div>
                <div class="card-body">
                    <button   class="btn btn-success add-mail-boxes">
                        Add Mail Box
                    </button>
                    <table id="mail_boxesTable" class="table">
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
<div class="modal fade" id="addMailBoxModal" tabindex="-1" role="dialog" aria-labelledby="addMailBoxModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMailBoxModalLabel">Add MailBox</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <form class="form" id="addMailBoxForm" action="{{ route('mail_boxes.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label>Subject:</label>
                            <input type="text" name="subject" class="form-control form-control-solid" required placeholder="Enter subject" />
                        </div>
                        <div class="form-group">
                            <label>Body:</label>
                            <textarea name="body" cols="30" rows="10" class="form-control form-control-solid"></textarea>
                        </div>
                        {{-- <div class="form-group">
                            <label>Status:</label>
                            <input type="text" name="phone" class="form-control form-control-solid" required placeholder="Enter phone" />
                        </div> --}}

                    </div>

                    <div class="card-footer">
                        <button type="submit" id="addFormSubmit" class="btn btn-primary mr-2">Add</button>
                        <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
<!-- Edit mail_boxes Modal -->
<div class="modal fade" id="editMailBoxModal" tabindex="-1" role="dialog" aria-labelledby="editMailBoxModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editMailBoxModalLabel">Edit mail_boxes</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <form class="form" id="editMailBoxForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label>Subject:</label>
                            <input type="text" name="subject" id="subject" class="form-control form-control-solid" required placeholder="Enter subject" />
                        </div>
                        <div class="form-group">
                            <label>Body:</label>
                            {{-- <input type="email" name="body" id="body" class="form-control form-control-solid" required placeholder="Enter body" /> --}}
                            <textarea name="body" id="body" cols="30" rows="10" class="form-control form-control-solid"></textarea>
                        </div>

                    </div>
                    <input type="hidden" name="mail_boxes_id" id="mail_boxes_id" />

                    <div class="card-footer">
                        <button type="submit" id="editFormSubmit" class="btn btn-primary mr-2">Update</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteMailBoxModal" tabindex="-1" aria-labelledby="deleteMailBoxModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteMailBoxModalLabel">Delete MailBox</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this mail?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteMailBoxForm" method="POST">
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
        // Handle form submission for adding a new mail_boxes
    $('#submitBtn').click(function() {
        $('#mail_boxesForm').submit();
    });


    var table = $('#mail_boxesTable').DataTable({
        // dom: 'Bfrtip',
        // responsive: true,
        paging: false,
        info: false,
        colReorder: true, // Enable ColReorder
        bPaginate: false,
        autoWidth: false,

        ajax: {
                url: "{{ route('mail_boxes.index') }}"
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
                data: 'subject'
                , title: 'Subject'

            }, {
                data: 'body'
                , title: 'Body'


            },{
                data: 'status',
                title: 'Status',
                render: function(data, type, row) {
                    let checked = data ? 'checked' : '';
                    return `
                        <div class="table_switch">
                            <div class="form-check form-switch">
                                <input class="form-check-input status" data-id="${row.id}" type="checkbox" id="flexSwitchCheck${row.id}" ${checked}>
                                <label class="form-check-label" for="flexSwitchCheck${row.id}"></label>
                            </div>
                        </div>
                    `;
                }
            },
            {
                data:"id",
                title: "Action",
                render : function(data,type,row){
                    // console.log(row);
                    let html = '<div class=""><a href="javascript:;" mail_boxes_id='+ data +' class="col-2 text-center p-2 mx-2 edit-mail-boxes btn-sm btn-success btn-icon" title="Edit details"><span class="svg-icon svg-icon-md"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none"fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><path d="M8,17.9148182 L8,5.96685884 C8,5.56391781 8.16211443,5.17792052 8.44982609,4.89581508 L10.965708,2.42895648 C11.5426798,1.86322723 12.4640974,1.85620921 13.0496196,2.41308426 L15.5337377,4.77566479 C15.8314604,5.0588212 16,5.45170806 16,5.86258077 L16,17.9148182 C16,18.7432453 15.3284271,19.4148182 14.5,19.4148182 L9.5,19.4148182 C8.67157288,19.4148182 8,18.7432453 8,17.9148182 Z" fill="#000000" fill-rule="nonzero" transform="translate(12.000000, 10.707409) rotate(-135.000000) translate(-12.000000, -10.707409) "></path><rect fill="#000000" opacity="0.3" x="5" y="20" width="15" height="2" rx="1"></rect></g></svg></span></a>'
                    html += '<a href="javascript:;" mail_boxes_id='+ data +' class="col-2 text-center p-2 mx-2 delete-mail-boxes btn-sm btn-success btn-icon" title="Delete Details"><span class="svg-icon svg-icon-md"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">        <rect x="0" y="0" width="24" height="24"></rect><path d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z" fill="#000000" fill-rule="nonzero"></path><path d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3"></path>    </g></svg>	    </span> </a>'

                        html += '</div>'
                    return html;
                }
            }
        ],

        dom: 'Bfrtip',


    });





    $(document).on('click', '.edit-mail-boxes', function () {
        let mail_boxes_id = $(this).attr('mail_boxes_id');
        $.ajax({
            url: "{{ route('mail_boxes.edit', ':id') }}".replace(':id', mail_boxes_id),
            method: "GET",
            beforeSend: function () {
                $("#editMailBoxForm").trigger('reset');
                $("#editMailBoxForm").attr('action', '{{ route("mail_boxes.update", ":id") }}'.replace(':id', mail_boxes_id));
                $("#editMailBoxModalLabel").html("Edit MailBox");
            },
            success: function (data) {
                console.log(data);
                $("#editMailBoxModal").modal('show');
                $("#subject").val(data.subject);
                $("#body").text(data.body);
                // $("#phone").val(data.phone);
                $("#mail_boxes_id").val(data.id);
            }
        });
    });



    $(document).on('click', '.delete-mail-boxes', function () {
        let mail_boxes_id = $(this).attr('mail_boxes_id');
        let deleteUrl = "{{ route('mail_boxes.destroy', ':id') }}".replace(':id', mail_boxes_id);

        $("#deleteMailBoxForm").attr('action', deleteUrl);
        $("#deleteMailBoxModal").modal('show');
    });

    $(document).on('click', '.add-mail-boxes', function () {
        $("#addMailBoxForm").attr('action', "{{ route('mail_boxes.store') }}");
        $("#addMailBoxModal").modal('show');
    });

    $(document).on('change', '.status', function() {
            console.log('status changed');
            var id = $(this).data('id');

            $.ajax({
                type: "put",
                data: {
                    _token: '{{ csrf_token() }}'
                },
                url: "{{ url('/mail_boxes/status') }}" + "/" + id,
                success: function(response) {
                    toastr.success(response)
                },
                error: function(err) {
                    console.log(err);
                }
            })

        });


});
</script>
@endsection
