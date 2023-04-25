@extends(backpack_view('blank'))

@php
  $defaultBreadcrumbs = [
    trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
    $crud->entity_name_plural => url($crud->route),
    trans('backpack::crud.list') => false,
  ];

  // if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
  $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp

@section('header')
  <div class="container-fluid">
    <h2>
      <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
      @if (str_contains(url()->current(), 'moderate') == false)
        <small id="datatable_info_stack">{!! $crud->getSubheading() ?? "" !!}</small>
      @endif
    </h2>
  </div>
@endsection

@section('content')
  <!-- Default box -->
  <div class="row">

    <!-- THE ACTUAL CONTENT -->
    <div class="{{ $crud->getListContentClass() }}">

        <div class="row mb-0">
          <div class="col-sm-6">
            @if ( $crud->buttons()->where('stack', 'top')->count() ||  $crud->exportButtons())
              <div class="d-print-none {{ $crud->hasAccess('create')?'with-border':'' }}">

                @include('crud::inc.button_stack', ['stack' => 'top'])

              </div>
            @endif
          </div>
          <div class="col-sm-6">
            <div id="datatable_search_stack" class="mt-sm-0 mt-2 d-print-none"></div>
          </div>
        </div>

        {{-- Backpack List Filters --}}
        @if ($crud->filtersEnabled())
          @include('crud::inc.filters_navbar')
        @endif
        @if ($crud->model->getTable() == "projects" && count($crud->getEntries()) == 0) <br>
          <div class="alert alert-warning">
            Please register an activity first
          </div>
        @else
        <table
          id="crudTable"
          class="bg-white table table-striped table-hover nowrap rounded shadow-xs border-xs mt-2"
          data-has-details-row="{{ $crud->getOperationSetting('detailsRow') ?? 0 }}"
          data-has-bulk-actions="{{ $crud->getOperationSetting('bulkActions') ?? 0 }}"
          cellspacing="0">
            <thead>
              <tr>
                {{-- Table columns --}}
                @foreach ($crud->columns() as $column)
                  <th
                    data-orderable="{{ var_export($column['orderable'], true) }}"
                    data-priority="{{ $column['priority'] }}"
                    {{--
                    data-visible-in-table => if developer forced field in table with 'visibleInTable => true'
                    data-visible => regular visibility of the field
                    data-can-be-visible-in-table => prevents the column to be loaded into the table (export-only)
                    data-visible-in-modal => if column apears on responsive modal
                    data-visible-in-export => if this field is exportable
                    data-force-export => force export even if field are hidden
                    --}}

                    {{-- If it is an export field only, we are done. --}}
                    @if(isset($column['exportOnlyField']) && $column['exportOnlyField'] === true)
                      data-visible="false"
                      data-visible-in-table="false"
                      data-can-be-visible-in-table="false"
                      data-visible-in-modal="false"
                      data-visible-in-export="true"
                      data-force-export="true"
                    @else
                      data-visible-in-table="{{var_export($column['visibleInTable'] ?? false)}}"
                      data-visible="{{var_export($column['visibleInTable'] ?? true)}}"
                      data-can-be-visible-in-table="true"
                      data-visible-in-modal="{{var_export($column['visibleInModal'] ?? true)}}"
                      @if(isset($column['visibleInExport']))
                         @if($column['visibleInExport'] === false)
                           data-visible-in-export="false"
                           data-force-export="false"
                         @else
                           data-visible-in-export="true"
                           data-force-export="true"
                         @endif
                       @else
                         data-visible-in-export="true"
                         data-force-export="false"
                       @endif
                    @endif
                  >
                    {{-- Bulk checkbox --}}
                    @if($loop->first && $crud->getOperationSetting('bulkActions'))
                      {!! View::make('crud::columns.inc.bulk_actions_checkbox')->render() !!}
                    @endif
                    {!! $column['label'] !!}
                  </th>
                @endforeach
                  
                @if ( $crud->buttons()->where('stack', 'line')->count() )
                  <th data-orderable="false"
                      data-priority="{{ $crud->getActionsColumnPriority() }}"
                      data-visible-in-export="false"
                      >{{ trans('backpack::crud.actions') }}</th>
                @endif
              </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
              <tr>
                {{-- Table columns --}}
                @foreach ($crud->columns() as $column)
                  <th>
                    {{-- Bulk checkbox --}}
                    @if($loop->first && $crud->getOperationSetting('bulkActions'))
                      {!! View::make('crud::columns.inc.bulk_actions_checkbox')->render() !!}
                    @endif
                    {!! $column['label'] !!}
                  </th>
                @endforeach

                @if ( $crud->buttons()->where('stack', 'line')->count() )
                  <th>{{ trans('backpack::crud.actions') }}</th>
                @endif
              </tr>
            </tfoot>
          </table>

          @if ( $crud->buttons()->where('stack', 'bottom')->count() )
          <div id="bottom_buttons" class="d-print-none text-center text-sm-left">
            @include('crud::inc.button_stack', ['stack' => 'bottom'])

            <div id="datatable_button_stack" class="float-right text-right hidden-xs"></div>
          </div>
          @endif
        @endif
    
        </div>

  </div>
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
                      <input type="hidden" id="equipment_id" value="">
                      <select name="entity" id="entity" class="form-control">

                          <option value="">-</option>
                          <option value="\App\Models\Bundle">Bundle</option>
                          <option value="\App\Models\Inventory">Inventory</option>                                
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
@endsection

@section('after_styles')
  <!-- DATA TABLES -->
  <link rel="stylesheet" type="text/css" href="{{ asset('packages/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('packages/datatables.net-fixedheader-bs4/css/fixedHeader.bootstrap4.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('packages/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}">

  <!-- CRUD LIST CONTENT - crud_list_styles stack -->
  @stack('crud_list_styles')
@endsection

@section('after_scripts')
@php
  $moderation = false;
  if ((str_contains(url()->current(), 'moderate'))) {
      $url = explode('/', url()->current());
      // dd($url) ;
      $moderation = $url[count($url) - 1];
  }
  @endphp
  @include('crud::inc.datatables_logic', ['moderation' => $moderation])
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
                    alert("qsdqs");
                    $('#addModal form')[0].reset();
                    $('#cancel-addto').trigger('click');
                }
            });
        }
    });
</script>
  <!-- CRUD LIST CONTENT - crud_list_scripts stack -->
  @stack('crud_list_scripts')
@endsection
