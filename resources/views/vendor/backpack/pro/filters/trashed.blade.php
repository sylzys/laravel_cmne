{{-- Trashed Filter Backpack CRUD filter --}}
<li filter-name="{{ $filter->name }}"
    filter-type="{{ $filter->type }}"
    filter-key="{{ $filter->key }}"
	filter-delete-without-trash="{{ var_export($filter->options['deleteWithoutTrash'] ?? false) }}"
	class="nav-item {{ Request::get($filter->name)?'active':'' }}">
    <a class="nav-link" href=""
		parameter="{{ $filter->name }}"
    	>{{ $filter->label }}</a>
  </li>


{{-- ########################################### --}}
{{-- Extra CSS and JS for this particular filter --}}

@push('crud_list_scripts')
    <script>
		jQuery(document).ready(function($) {
			let filter = $("li[filter-key={{ $filter->key }}] a");
			let filterParameter = filter.attr('parameter');
			let tableUrl = $("#crudTable").DataTable().ajax.url();

			if (URI(tableUrl).hasQuery(filterParameter)) {
				$('#bottom_buttons').find('.trash-button').not('bulk-trash-button').show();
				$('#bottom_buttons').find('.bulk-trash-button').hide();
			} else {
				$('#bottom_buttons').find('.trash-button').not('.bulk-trash-button').hide();
				
			}
			filter.click(function(e) {
				e.preventDefault();

				var parameter = $(this).attr('parameter');

		    	// behaviour for ajax table
				var ajax_table = $("#crudTable").DataTable();
				var current_url = ajax_table.ajax.url();

				if (URI(current_url).hasQuery(parameter)) {
					var new_url = URI(current_url).removeQuery(parameter, true);
				} else {
					var new_url = URI(current_url).addQuery(parameter, true);
				}

				new_url = normalizeAmpersand(new_url.toString());

				// replace the datatables ajax url with new_url and reload it
				ajax_table.ajax.url(new_url).load();

				// add filter to URL
				crud.updateUrl(new_url);

				// mark this filter as active in the navbar-filters
				if (URI(new_url).hasQuery('{{ $filter->name }}', true)) {
					$("li[filter-key={{ $filter->key }}]").removeClass('active').addClass('active');
                    $('#remove_filters_button').removeClass('invisible');
					$('#bottom_buttons').find('.trash-button').not('bulk-trash-button').show();
					$('#bottom_buttons').find('.bulk-trash-button').hide();			
				}
				else
				{
					$("li[filter-key={{ $filter->key }}]").trigger("filter:clear");
				}
			});

			// clear filter event (used here and by the Remove all filters button)
			$("li[filter-key={{ $filter->key }}]").on('filter:clear', function(e) {
				$('#bottom_buttons').find('.trash-button').not('.bulk-trash-button').hide();
				$('#bottom_buttons').find('.bulk-trash-button').show();
				$("li[filter-key={{ $filter->key }}]").removeClass('active');
			});
		});
	</script>
@endpush
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
