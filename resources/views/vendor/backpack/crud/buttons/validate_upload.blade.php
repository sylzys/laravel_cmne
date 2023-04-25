@php
	$url = explode('/', url()->current());
    $id = $url[count($url) - 1];
@endphp
    <a href="javascript:void(0)" onclick="validateEntry(this)" class="btn btn-primary" id="validate_upload" data-route= "{{ url($crud->route.'/' . $id . '/validate-upload') }} " data-button-type="validate"><i class="la la-check-circle"></i> Validate</a>
{{-- @if ($crud->hasAccess('delete'))
	<a href="javascript:void(0)" onclick="deleteEntry(this)" data-route="{{ url($crud->route.'/'.$entry->getKey()) }}" class="btn btn-sm btn-link" data-button-type="delete"><i class="la la-trash"></i> {{ trans('backpack::crud.delete') }}</a>
@endif --}}

{{-- Button Javascript --}}
{{-- - used right away in AJAX operations (ex: List) --}}
{{-- - pushed to the end of the page, after jQuery is loaded, for non-AJAX operations (ex: Show) --}}
@push('after_scripts') @if (request()->ajax()) @endpush @endif
<script>

	if (typeof validateEntry != 'function') {
	  $("[data-button-type=validate]").unbind('click');

	  function validateEntry(button) {
		// ask for confirmation before deleting an item
		// e.preventDefault();
		var route = $(button).attr('data-route');

		swal({
		  title: "{!! trans('backpack::base.warning') !!}",
		  text: "Are you sure you want to validate this upload ?",
		  icon: "warning",
		  buttons: ["{!! trans('backpack::crud.cancel') !!}", "{!! trans('backpack::crud.yes') !!}"],
		  dangerMode: true,
		}).then((value) => {
			if (value) {
				$.ajax({
			      url: route,
			      type: 'POST',
			      success: function(result) {
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
		                    text: "The upload has been validated, notifying the user.",
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
				              	title: "Not validated",
	                            text: "There was as arror validating this upload",
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
		              	title: "Not validated",
                        text: "There was as arror validating this upload",
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
	// crud.addFunctionToDataTablesDrawEventQueue('deleteEntry');
</script>
@if (!request()->ajax()) @endpush @endif
