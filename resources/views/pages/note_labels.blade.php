@extends('main')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3 class="card-title mr-auto">Notes by Label: {{ $labelId }}</h3>
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
                    <label class="modal-title text-text-bold-600" id="modalLabel">Add/Edit Note</label>
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
                                    <input type="text" class="form-control" name="title" id="title"
                                        placeholder="Title">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <textarea class="form-control" name="body" id="body" placeholder="Content" rows="10"></textarea>
                                    <div class="invalid-feedback" id="body-error">This field cannot be empty</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer pull-right">
                        <span id="saving"></span>
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
                url: "{{ url('/note-labels/get-data') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id:"{{ Request::segment(2) }}"
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: '#',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'note.title',
                    title: 'Title',
                },
                {
                    data: 'note.body',
                    title: 'Content'
                },
                {
                    data: 'label.name',
                    title: 'Label'
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
            $('#title').val('')
            $('#body').val('')
            $('#saving').text('')
            $('#body-error').removeClass('d-block')
        })

        function editData(data_id) {
            $.ajax({
                method: 'GET',
                url: "{{ url('notes') }}/" + data_id + "/edit",
                dataType: "json",
                success: function(data) {
                    $('#id').val(data.id)
                    $('#title').val(data.title)
                    $('#body').val(data.body)
                }
            })
        }

        function save() {
            const id = $('#id').val();
            const title = $('#title').val();
            const body = $('#body').val();

            if (!body) {
                $('#body').addClass('is-invalid')
                $('#body-error').addClass('d-block')
                $('#saving').text('Not Saved')
            } else {
                let url = ''
                let method = ''
                if (id) {
                    url = "{{ url('notes') }}/" + id
                    method = 'PUT'
                } else {
                    url = "{{ url('notes') }}"
                    method = 'POST'
                }

                $.ajax({
                    type: "POST",
                    url: url,
                    dataType: "JSON",
                    data: {
                        _token: "{{ csrf_token() }}",
                        _method: method,
                        title: title,
                        body: body
                    },
                    beforeSend: function() {
                        $('.close-editor').prop('disabled', true)
                    },
                    success: function(data) {
                        if (data.lastId) $('#id').val(data.lastId);
                        $('#saving').text('Saved')
                        $('.close-editor').prop('disabled', false)
                    },
                    error: function() {
                        $('#saving').text('Error Saving!')
                        $('.close-editor').prop('disabled', false)
                    }
                })
            }
        }

        let noteTimeout
        $('#title, #body').on('input', function() {
            $('#saving').text('Saving...')
            $('#body').removeClass('is-invalid')
            $('#body-error').removeClass('d-block')

            clearTimeout(noteTimeout)
            noteTimeout = setTimeout(function() {
                save()
            }, 500);
        })

        $('.close-editor').click(function() {
            table.ajax.reload();
        })

        $('#table').on('click', '.btn-delete', function() {
            const id = $(this).data('id')
            const title = $(this).data('title')

            Swal.fire({
                title: 'Are you sure?',
                html: 'You are about to delete note "' + title.bold() +
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
                        url: "{{ url('notes') }}/" + id,
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
                                'Your note has been deleted.',
                                'success'
                            ).then(() => {
                                $('.btn').prop('disabled', false)
                                table.ajax.reload();
                            })
                        },
                        error: () => {
                            Swal.fire(
                                'Failed!',
                                'Failed to delete note.',
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
