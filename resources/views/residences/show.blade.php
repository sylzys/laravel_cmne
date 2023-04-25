@extends(backpack_view('blank'))
@php
$entry = $crud->entry;
$galery = json_decode($entry->galery);
@endphp

@section('content')
<div class="container">
    <div class="main-body" id="residence-container">
          <div class="row">
            <div class="col-md-12" style="text-align:center;">
            <img class="img-fluid " src="{{ url('residences/'.$entry->header) }}" height=250 alt="">
            </div>
            <div class="col-md-12">
            <h2 class="residence-name">{{$entry->name}} - <i class="nav-icon la la-home"></i>{{count($entry->housings) ?? 0 }}</h2><br>
              @if ($entry->amenities && count($entry->amenities) > 0)
                <div class="amenities" >
                @foreach ($entry->amenities as $key => $value)
                <span class="badge badge-pill badge-success">{{$value->name}}</span>
                @endforeach
                </div>
                <hr>
                {{ $entry->description}}
              @endif
            <!-- <p>{{$entry->description}}</p> -->
            <div class="cta">
              <button class="btn btn-primary" id="btn-residence">Plus d'infos</button>
            </div>
            <hr>
            <h2>Galerie</h2>
            <hr class="mt-2 mb-5">
            <div class="row text-center text-lg-start">
              @foreach ($galery as $key => $value)
              <div class="col-lg-3 col-md-4 col-6">
              <a href="#" class="d-block mb-4 h-100">
                <img class="img-fluid img-thumbnail" src="{{ url('residences/'.$value) }}" height=250 width=250 alt="">
              </a>
            </div>
            @endforeach
            </div>
            </div>
          </div>

        </div>
    </div>
@endsection