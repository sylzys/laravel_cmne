@php
    $type = $entry->bundle_id == null ? 'inventory' : 'bundle';
@endphp
<a href="{{ url($crud->route.'/moderate/'. $type . '/' . $entry->getKey()) }}" class="btn btn-sm btn-link"><i class="la la-check-circle"></i> Moderate</a>
