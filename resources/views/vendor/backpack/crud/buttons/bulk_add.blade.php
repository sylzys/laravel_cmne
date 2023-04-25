@if ($crud->hasAccess('bulkAdd') && $crud->get('list.bulkActions'))
	<a href="javascript:void(0)" onclick="bulkAddEntries(this)" class="btn btn-sm btn-primary bulk-button"><i class="la la-copy"></i> Add selection </a>
	{{-- {{ trans('backpack::crud.add') }}</a> --}}
@endif

@push('after_scripts')
<script>
	if (typeof bulkAddEntries != 'function') {
	  function bulkAddEntries(button) {

	      if (typeof crud.checkedItems === 'undefined' || crud.checkedItems.length == 0)
	      {
  	        new Noty({
	          type: "warning",
	          text: "<strong>{!! trans('backpack::crud.bulk_no_entries_selected_title') !!}</strong><br>{!! trans('backpack::crud.bulk_no_entries_selected_message') !!}"
	        }).show();

	      	return;
	      }

	      var message = "{!! trans('backpack::crud.bulk_add_are_you_sure') !!}";
	      message = message.replace(":number", crud.checkedItems.length);

	      // show confirm message
	      swal({
			  title: "{!! trans('backpack::base.warning') !!}",
			  text: message,
			  icon: "warning",
			  buttons: {
			  	cancel: {
				  text: "{!! trans('backpack::crud.cancel') !!}",
				  value: null,
				  visible: true,
				  className: "bg-secondary",
				  closeModal: true,
				},
			  	delete: {
				  text: "{{ trans('backpack::crud.add') }}",
				  value: true,
				  visible: true,
				  className: "bg-primary",
				}
			  },
			}).then((value) => {
				if (value) {
					var ajax_calls = [];
		      		var add_route = "{{ url($crud->route) }}/bulk-add/11";

					// submit an AJAX delete call
					$.ajax({
						url: add_route,
						type: 'POST',
						data: { entries: crud.checkedItems },
						success: function(result) {
						  // Show an alert with the result
		    	          new Noty({
				            type: "success",
				            text: "<strong>{!! trans('backpack::crud.bulk_add_sucess_title') !!}</strong><br>"+crud.checkedItems.length+" {!! trans('backpack::crud.bulk_add_sucess_message') !!}"
				          }).show();

						  crud.checkedItems = [];
						  crud.table.draw(false);
						},
						error: function(result) {
						  // Show an alert with the result
		    	          new Noty({
				            type: "danger",
				            text: "<strong>{!! trans('backpack::crud.bulk_add_error_title') !!}</strong><br>"+crud.checkedItems.length+" {!! trans('backpack::crud.bulk_add_error_message') !!}"
				          }).show();
						}
					});
				}
			});
      }
	}
</script>
@endpush