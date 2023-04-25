@if($entry->brand && $entry->brand->name != "GENERIC")
    <form style="display:inline;" action="{{ url($crud->route.'/'.$entry->getKey().'/use-as-reference') }}" method="POST">
        @csrf
        <button class="btn btn-sm btn-link" type="submit"><i class="la la-folder-plus"></i> Use as reference</button>
    </form>
@endif
