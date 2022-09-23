@extends('main')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3 class="card-title mr-auto">Notes Without Label</h3>
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

    <div class="modal fade" id="modal-add-label" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <label class="modal-title text-text-bold-600" id="modalLabel">Add Note Label</label>
                    <button type="button" class="close close-editor" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form">
                    <div class="modal-body">
                        <input type="hidden" class="form-control" name="id" id="id-add">

                        <div class="form-group">
                            <label>Select Label</label>
                            <select class="form-control select2" id="label" style="width: 100%;"></select>
                            <div class="invalid-feedback" id="label-error">This field cannot be empty</div>
                        </div>
                        <div class="form-group d-flex">
                            <button type="button" class="btn btn-sm btn-success mr-auto" onclick="addLabelOptions()">
                                Refresh
                            </button>
                            <a href="{{ url('/labels') }}" target="_blank" class="btn btn-sm btn-primary">Add new label</a>
                        </div>
                    </div>
                    <div class="modal-footer pull-right">
                        <button type="button" class="btn btn-sm btn-primary" id="save-label">
                            Save
                        </button>
                        <button type="button" id="cancel-add-label" class="btn btn-sm btn-danger" data-dismiss="modal">
                            Cancel
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
                url: "{{ url('/notes/getNotes') }}",
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
                    data: 'content',
                    title: 'Content',
                    render: function(data) {
                        function shorten(str, maxLen, separator = ' ') {
                            if (str.length <= maxLen) return str;
                            return str.substr(0, str.lastIndexOf(separator, maxLen)) + '...';
                        }
                        let titlePattern = /(?<=<note-title>)(.*?)(?=<\/note-title>)/gmi
                        let bodyPattern = /(?<=<note-body>)(.*?)(?=<\/note-body>)/gmi
                        let title = data.match(titlePattern);
                        title = shorten(title[0], 60)
                        let body = data.match(bodyPattern);
                        body = shorten(body[0], 100)
                        return `<span style="font-size:1.1em;font-weight:600;">${title}</span><hr>${body}`
                    }
                },
                {
                    data: 'action',
                    title: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });

        $('#modal').on('hidden.bs.modal', function() {
            $('#id').val('')
            $('#title').val('')
            $('#body').val('')
            $('#saving').text('')
            $('#body-error').removeClass('d-block')
        })

        $('#modal-add-label').on('hidden.bs.modal', function() {
            $('#label-error').removeClass('d-block')
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

        $('.close-editor, #cancel-add-label').click(function() {
            table.ajax.reload();
        })

        $('#table').on('click', '.btn-delete', function() {
            const id = $(this).data('id')
            const title = $(this).data('title').toString()

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

        function addLabelOptions() {
            $('#label').html('')

            $.ajax({
                method: "GET",
                url: "{{ url('labels') }}/get",
                dataType: "json",
                success: function(data) {
                    $('#label').append(`<option value=''>Select Label</option>`)
                    data.forEach(dt => {
                        $('#label').append(`<option value='${dt.id}'>${dt.name}</option>`)
                    });
                }
            })
        }

        $('#table').on('click', '.btn-add-label', function() {
            $('#id-add').val($(this).attr('data-id'))

            addLabelOptions()
        })

        $('#save-label').click(() => {
            $('#label-error').removeClass('d-block')

            const note_id = $('#id-add').val()
            const label_id = $('#label').val()

            if (!label_id) {
                $('#label-error').addClass('d-block')
            } else {
                $.ajax({
                    method: "POST",
                    url: "{{ url('notes') }}/set-label",
                    dataType: 'json',
                    data: {
                        _token: "{{ csrf_token() }}",
                        note_id: note_id,
                        label_id: label_id
                    },
                    beforeSend: () => {
                        $('.btn').prop('disabled', true)
                    },
                    success: () => {
                        toastr.success('Success adding label!')
                        table.ajax.reload();
                        $('.btn').prop('disabled', false)
                        $('#modal-add-label').modal('hide');
                    },
                    error: () => {
                        toastr.error('Error adding label')
                        $('.btn').prop('disabled', false)
                        $('#modal-add-label').modal('hide');
                        table.ajax.reload();
                    }
                })
            }
        })
    </script>
@endsection
