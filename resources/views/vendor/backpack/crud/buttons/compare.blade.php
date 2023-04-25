@if ($crud->hasAccess('compare') && $crud->get('list.bulkActions'))
	<a href="javascript:void(0)" onclick="compareEntries(this)" class="btn btn-sm btn-secondary bulk-button"><i class="la la-copy"></i> Compare</a/>
		{{-- {{ trans('backpack::crud.compare') }}</a> --}}
@endif

@push('after_scripts')
<script>
	if (typeof compareEntries != 'function') {
	  function compareEntries(button) {

	      if (typeof crud.checkedItems === 'undefined' || crud.checkedItems.length == 0)
	      {
	      	new Noty({
	          type: "warning",
	          text: "<strong>{!! trans('backpack::crud.bulk_no_entries_selected_title') !!}</strong><br>{!! trans('backpack::crud.bulk_no_entries_selected_message') !!}"
	        }).show();

	      	return;
	      }

	    //   var message = ("{!! trans('backpack::crud.bulk_delete_are_you_sure') !!}").replace(":number", crud.checkedItems.length);
	    var button = $(this);
		var ajax_calls = [];
		var compare_route = "{{ url($crud->route) }}/compare";
		var entries = crud.checkedItems.join('/');
		
		// submit an AJAX delete call
		$.ajax({
			url: compare_route,
			type: 'POST',
			data: { entries: crud.checkedItems},
			success: function(result) {
				window.location.replace("/compare?type=" + JSON.parse(result).crud.entity_name + "&entries=" + entries);
			},
			error: function(result) {
				// Show an alert with the result
				new Noty({
					type: "warning",
					text: "<strong>{!! trans('backpack::crud.bulk_delete_error_title') !!}</strong><br>{!! trans('backpack::crud.bulk_delete_error_message') !!}"
				}).show();
			}
		});
      }
	}
</script>
@endpush
