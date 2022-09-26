@extends('main')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3 class="card-title mr-auto">Labels</h3>
                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal">+ Add New</button>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="table" class="table table-bordered table-striped"></table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>

    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <label class="modal-title text-text-bold-600" id="modalLabel">Add/Edit Label</label>
                    <button type="button" class="close close-editor" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form">
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="hidden" class="form-control" name="id" id="id">

                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" name="name" id="name"
                                        placeholder="Title">
                                    <div class="invalid-feedback" id="name-error">This field cannot be empty</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer pull-right">
                        <span id="saving"></span>
                        <button type="button" class="btn btn-sm btn-primary" id="save">
                            <i class="icon-close"></i>
                            Save
                        </button>
                        <button type="button" class="btn btn-sm btn-danger close-editor" data-dismiss="modal">
                            <i class="icon-close"></i>
                            Close
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('/labels/getLabels') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: '#',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'name',
                    title: 'Name',
                },
                {
                    data: 'action',
                    title: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });

        $('#modal').on('show.bs.modal', function() {
            $('#id').val('')
            $('#name').val('')
            $('#saving').text('')
            $('#name-error').removeClass('d-block')
        })

        function editData(data_id) {
            $.ajax({
                method: 'GET',
                url: "{{ url('labels') }}/" + data_id + "/edit",
                dataType: "json",
                success: function(data) {
                    $('#id').val(data.id)
                    $('#name').val(data.name)
                }
            })
        }

        function save() {
            const id = $('#id').val();
            const name = $('#name').val();

            if (!name) {
                $('#name').addClass('is-invalid')
                $('#name-error').addClass('d-block')
                $('#saving').text('Not Saved')
            } else {
                let url = ''
                let method = ''
                if (id) {
                    url = "{{ url('labels') }}/" + id
                    method = 'PUT'
                } else {
                    url = "{{ url('labels') }}"
                    method = 'POST'
                }

                $.ajax({
                    type: "POST",
                    url: url,
                    dataType: "JSON",
                    data: {
                        _token: "{{ csrf_token() }}",
                        _method: method,
                        name: name,
                    },
                    beforeSend: function() {
                        $('#saving').text('Saving...')
                        $('.close-editor').prop('disabled', true)
                    },
                    success: function(data) {
                        if (data.lastId) $('#id').val(data.lastId);
                        $('#saving').text('Saved')
                        $('.close-editor').prop('disabled', false)
                        table.ajax.reload();
                    },
                    error: function() {
                        $('#saving').text('Error Saving!')
                        $('.close-editor').prop('disabled', false)
                        table.ajax.reload();
                    }
                })
            }
        }

        $('#save').click(function() {
            $('#saving').text('Saving...')
            $('#name').removeClass('is-invalid')
            $('#name-error').removeClass('d-block')
            save()
        })

        $(document).on("submit", "form", function(e) {
            e.preventDefault();
            $('#saving').text('Saving...')
            $('#name').removeClass('is-invalid')
            $('#name-error').removeClass('d-block')
            save()
        });

        $('.close-editor').click(function() {
            table.ajax.reload();
        })

        $('#table').on('click', '.btn-delete', function() {
            const id = $(this).data('id')
            const name = $(this).data('name')

            Swal.fire({
                title: 'Are you sure?',
                html: 'You are about to delete label "' + name.bold() +
                    '". You won\'t be able to revert this!',
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: "{{ url('labels') }}/" + id,
                        dataType: 'JSON',
                        data: {
                            _token: "{{ csrf_token() }}",
                            _method: 'DELETE'
                        },
                        beforeSend: () => {
                            $('.btn').prop('disabled', true)
                        },
                        success: () => {
                            Swal.fire(
                                'Success!',
                                'Your label has been deleted.',
                                'success'
                            ).then(() => {
                                $('.btn').prop('disabled', false)
                                table.ajax.reload();
                            })
                        },
                        error: () => {
                            Swal.fire(
                                'Failed!',
                                'Failed to delete label. You might still have notes under this label',
                                'error'
                            ).then(() => {
                                $('.btn').prop('disabled', false)
                                table.ajax.reload();
                            })
                        }
                    })
                }
            })
        })
    </script>
@endsection
