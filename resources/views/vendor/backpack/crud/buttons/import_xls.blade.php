{{-- <a href="{{ url($crud->route.'/uplo') }} " class="btn btn-primary" data-style="zoom-in"><span class="ladda-label"><i class="la la-plus-circle"></i> Request equipment</span></a> --}}

<a class="btn btn-primary" data-toggle="modal" data-target="#addModal" href="#">
    <i class="la la-upload"></i> Upload equipments
</a>

@section('after_scripts')
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Upload equipment list</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="upload-form">
                        <div class="hint">
                            <p>Please use the <a href="{{asset('/assets/cawo_inventory_template.xlsx')}}">following template </a>to prepare your upload.</p><br>
                        <p> Accepted formats: .xls, .xlsx, .csv (comma delimited)<br>
                            <span id="error" class="error" ></span><br><br></div>
                            <input type="hidden" id="id" value="{{ $entry == null ? -1 : $entry->getKey() }}">
                        <div class="form-group col-sm-12" element="div">
                            {{-- <input type="hidden" id="id" value="{{ $entry->getKey() }}"> --}}
                            
                            <div data-init-function="bpFieldInitUploadElement" data-field-name="file" class="form-group col-sm-3" element="div" data-initialized="true">
                                <div class="backstrap-file ">
                                    {{-- <input id="file" type="file" name="file" class="file_input backstrap-file-input"> --}}
                                    
                                    <input type="file" id="file_upload" name='file' class="file_input backstrap-file-input">
                                    <label class="backstrap-file-label" for="customFile"></label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="cancel-addto" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">

    function get_detail($fileObj)
    {
        console.log("Using", $fileObj);
        var size = $('#file_upload')[0].files[0].size;
        var extension = $('#file_upload').val().replace(/^.*\./, ''); 
        console.log("File Size : "+size+" <br>Extension : "+extension+"");
        if (extension == 'xls' || extension == 'xlsx' || extension == 'csv') {
            if(size <= 5485760)
            {
                $('#submit').removeAttr('disabled');
            }
            else
            {
                $('#submit').attr('disabled', 'disabled');
                return ('File size must be less than 5MB');
            }
        }
        else
        {
            $('#submit').attr('disabled', 'disabled');
            return ('File format must be .xls, .xlsx or .csv');
        }
        return -1;
    }

    $('#file').on('change', function() {
        get_detail($(this));
    });
    $("#submit").click(function(e) {

        $verif = get_detail($);
        if ($verif != -1) {
            $('#error').html($verif);
            return;
        }
        var formData = new FormData($('#upload-form')[0]);
        $.ajax({
            type: "POST",
            url: '/api/modal/upload-inventory',
            data: formData,
            cache:false,
            contentType: false,
            processData: false,
            // dataType: "json",
            success: function(response) {
                alert("qsdqs");
                $('#addModal form')[0].reset();
                $('#cancel-addto').trigger('click');
            }
        });
    });
    </script>
@endsection
