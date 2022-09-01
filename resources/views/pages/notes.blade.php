@extends('main')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Notes</h3>
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
                    data: 'title',
                    title: 'Title',
                },
                {
                    data: 'body',
                    title: 'Content'
                },
                {
                    data: 'action',
                    title: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });

        function editData(data_id) {
            $('#id').val('')
            $('#title').val('')
            $('#body').val('')
            $('#saving').text('')
            $('#body-error').removeClass('d-block')

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
                $.ajax({
                    type: "POST",
                    url: "{{ url('notes') }}/" + id,
                    dataType: "JSON",
                    data: {
                        _token: "{{ csrf_token() }}",
                        _method: "PUT",
                        id: id,
                        title: title,
                        body: body
                    },
                    beforeSend: function() {
                        $('#cancel').prop('disabled', true)
                    },
                    success: function() {
                        $('#saving').text('Saved')
                        $('#cancel').prop('disabled', false)
                    },
                    error: function() {
                        $('#saving').text('Error Saving!')
                        $('#cancel').prop('disabled', false)
                    }
                })
            }
        }

        let noteTimeout = null
        $('#title, #body').on('input', function() {
            $('#saving').text('Saving...')
            $('#body').removeClass('is-invalid')
            $('#body-error').removeClass('d-block')

            clearTimeout(noteTimeout)
            noteTimeout = setTimeout(function() {
                if ($('#id').val() !== '') save()
            }, 500);
        })

        $('.close-editor').click(function() {
            table.ajax.reload();
        })
    </script>
@endsection
