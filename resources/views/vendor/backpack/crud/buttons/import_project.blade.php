	{{-- <a href="javascript:void(0)" onclick="importProject(this)" data-route="{{ url($crud->route.'/import') }}" class="btn btn-sm btn-link" data-button-type="delete"><i class="la la-trash"></i> {{ trans('backpack::crud.delete') }}</a> --}}

	<a class="btn btn-primary" data-toggle="modal" data-target="#importModal" aria-controls="importModal" aria-expanded="false" data-style="zoom-in"><span class="ladda-label"><i class="la la-file-upload"></i> {{ trans('backpack::crud.import_project') }}</span></a>

<div class="modal fade" id="importModal" tabindex="-1" role="document" aria-labelledby="importModalLabel"
aria-hidden="true">
<div class="modal-dialog import-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="importModalLabel">Import project</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form id="import-project-form" enctype="multipart/form-data" >
                <div class="form-group col-sm-12" element="div"> <label>Upload the JSON file</label>
					<input type="file" name="file" id="file" class="form-control" />
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" id="cancel" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" data-route="{{ url($crud->route.'/import') }}" id="importProject" class="btn btn-primary">Import</button>
        </div>
    </div>
</div>
</div>


{{-- Button Javascript --}}
{{-- - used right away in AJAX operations (ex: List) --}}
{{-- - pushed to the end of the page, after jQuery is loaded, for non-AJAX operations (ex: Show) --}}
@push('after_scripts') @if (request()->ajax()) @endpush @endif
<script>
	$('#importProject').click(function (e) {
		e.preventDefault();
		importProject($(this).attr('data-route'));
	});
	if (typeof importProject != 'function') {
	  $("[data-button-type=delete]").unbind('click');

	  function importProject(route) {
		// ask for confirmation before deleting an item
		// e.preventDefault();
		// var route = $(button).attr('data-route');
		swal({
		  title: "{!! trans('backpack::base.warning') !!}",
		  text: "{!! trans('backpack::crud.import_confirm') !!}",
		  icon: "warning",
		  buttons: ["{!! trans('backpack::crud.cancel') !!}", "{!! trans('backpack::crud.import') !!}"],
		  dangerMode: true,
		}).then((value) => {
			if (value) {
				$.ajax({
					url: route,
					data: new FormData($('#import-project-form')[0]),
					type: 'POST',
					contentType: false,
					cache: false,
					processData:false,
			      success: function(result) {
					console.log('result, ', result);
			          if (result == 1) {
						  // Redraw the table
						  if (typeof crud != 'undefined' && typeof crud.table != 'undefined') {
							  // Move to previous page in case of deleting the only item in table
							  if(crud.table.rows().count() === 1) {
							    crud.table.page("previous");
							  }

							  crud.table.draw(false);
						  }

			          	  // Show a success notification bubble
			              new Noty({
		                    type: "success",
		                    text: "{!! '<strong>'.trans('backpack::crud.import_confirmation_title').'</strong><br>'.trans('backpack::crud.import_confirmation_message') !!}"
		                  }).show();

			              // Hide the modal, if any
			              $('.modal').modal('hide');
			          } else {
			              // if the result is an array, it means
			              // we have notification bubbles to show
			          	  if (result instanceof Object) {
			          	  	// trigger one or more bubble notifications
			          	  	Object.entries(result).forEach(function(entry, index) {
			          	  	  var type = entry[0];
			          	  	  entry[1].forEach(function(message, i) {
					          	  new Noty({
				                    type: type,
				                    text: message
				                  }).show();
			          	  	  });
			          	  	});
			          	  } else {// Show an error alert
				              swal({
				              	title: "{!! trans('backpack::crud.import_confirmation_not_title') !!}",
	                            text: "{!! trans('backpack::crud.import_confirmation_not_message') !!}",
				              	icon: "error",
				              	timer: 4000,
				              	buttons: false,
				              });
			          	  }
			          }
			      },
			      error: function(result) {
			          // Show an alert with the result
			          swal({
		              	title: "{!! trans('backpack::crud.import_json_error_title') !!}",
                        text: "{!! trans('backpack::crud.import_json_error_message') !!}",
		              	icon: "error",
		              	timer: 4000,
		              	buttons: false,
		              });
			      }
			  });
			}
		});

      }
	}

	// make it so that the function above is run after each DataTable draw event
	// crud.addFunctionToDataTablesDrawEventQueue('importProject');
</script>
@if (!request()->ajax()) @endpush @endif
