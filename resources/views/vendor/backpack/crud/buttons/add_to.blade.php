<a class="btn btn-sm btn-link" data-toggle="modal" data-target="#addModal" href="#">
    <i class="la la-angle-double-left"></i> Add to
</a>
@section('after_scripts')
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add equipment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group col-sm-12" element="div"> <label>Add to</label>
                            <input type="hidden" id="id" value="{{ $entry->getKey() }}">
                            <select name="entity" id="entity" class="form-control">

                                <option value="">-</option>
                                <option value="\App\Models\Bundle">Bundle</option>
                                <option value="\App\Models\Inventory">Inventory</option>
                                @if (in_array($entry->category_id, [1, 2])) {{-- 1 = IT, 2 = Audio&video --}}
                                    <option value="\App\Models\Serviceroom">Service room</option>
                                @endif
                            </select>
                        </div>
                        <div class="form-group col-sm-12" element="div"> <label>Select the target</label>
                            <select name="target" id="target" class="form-control">
                                <option value="">Make a choice above</option>
                            </select>
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
        $("#entity").change(function(e) {
            $entity = $(this).children("option:selected").val();
            $.ajax({
                type: "GET",
                url: '/api/modal/entities',
                data: {
                    'entity': $entity
                },
                dataType: "json",
                success: function(response) {
                    $options = '<option value="">-</option>';
                    response.forEach(element => {
                        $options += "<option value='" + element.id + "'>" + element.name +
                            "</option>";
                    });
                    console.log($options);
                    $('#target').html($options);
                }
            });
        });


        $("#submit").click(function(e) {
            $target = $('#target').val();
            $entity = $('#entity').val();
            console.log($entity);
            if($entity.trim() != '') {
                $.ajax({
                    type: "POST",
                    url: '/api/modal/link-entities',
                    data: {
                        'target': $target,
                        'entity': $entity,
                        'id': $("#id").val()
                    },
                    dataType: "json",
                    success: function(response) {
                        $('#addModal form')[0].reset();
                        $('#cancel-addto').trigger('click');
                    }
                });
            }
        });
    </script>
@endsection
