@extends(backpack_view('blank'))
@php
$entry = $crud->entry;
@endphp

@section('content')
<div class="container" id="housing-container">
    <div class="main-body">
          <div class="row">
            <div class="col-md-12" style="text-align:center;">
            <img class="img-fluid " src="{{ $entry->header }}" height=250 alt="">
            </div>
            <div class="col-md-12">
            <h2 class="housing-name">{{$entry->name}} - {{$entry->residence->name}}</h2>
            <div id="housing-details">
              <span><i class="la la-expand">{{ strtoupper($entry->type)}}</i></span>
              <span><i class="la la-compass">{{ $entry->orientation}}</i></span>
              <span><i class="la la-bed">{{ $entry->bedrooms}}</i></span>
              <span><i class="la la-bath">{{ $entry->bathrooms}}</i></span>
            </div>
              @if ($entry->amenities && count($entry->amenities) > 0)
                <div class="amenities" >
                @foreach ($entry->amenities as $key => $value)
                <span class="badge badge-pill badge-success">{{$value->name}}</span>
                @endforeach
                </div>
                <hr>
              @endif
              {{ $entry->description}}
              <div class="cta">
                <button class="btn btn-success btn-lg" id="btn-housing">Plus d'infos</button>
              </div>
            <!-- <p>{{$entry->description}}</p> -->
            <hr>
            <h2>Galerie</h2>
            <hr class="mt-2 mb-5">
            <div class="row text-center text-lg-start">
              @foreach ($entry->galery as $key => $value)
              <div class="col-lg-3 col-md-4 col-6">
              <a href="#" class="d-block mb-4 h-100">
                <img class="img-fluid img-thumbnail" src="{{ url('housings/'.$value) }}" height=250 width=250 alt="">
              </a>
            </div>
            @endforeach
            </div>
            </div>
          </div>

        </div>
    </div>
@endsection