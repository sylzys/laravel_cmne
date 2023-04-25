@extends(backpack_view('blank'))
@php
$entry = $crud->entry;
$galery = json_decode($entry->galery);
foreach ($galery as $key => $value) {
    $galery[$key] = url('residences/' . $value);
    print($galery[$key]);
}
@endphp

@section('content')
<div class="container">
    <div class="main-body">
          <div class="row gutters-sm">
            <div class="col-md-4 mb-3">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex flex-column align-items-center text-center">
                  <img src="{{ url('users/' . $entry->picture) }}" alt="">
                  <div class="mt-3">
                      <p class="text-muted font-size-sm">
                      </p>
                      <button class="btn btn-outline-primary">Message</button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card mt-3">
                <ul class="list-group list-group-flush">
                  <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                    <h6 class="mb-0">
                    <i class="las la-phone"></i><span class="text"> </span>
                  </li>
                  <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                    <h6 class="mb-0">
                    <i class="las la-at"></i><span class="text"> </span>
                  </li>
                  <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                    <h6 class="mb-0">
                    <i class="las la-map-marker-alt "></i><span class="text"></span>
                  </li>
                </ul>
              </div>
            </div>
            <div class="col-md-8">

            <hr class="mt-2 mb-5">

            <div class="row text-center text-lg-start">
              @foreach ($galery as $key => $value)
                <img src="{{ url($value) }}" alt="">
            @endforeach
            </div>




            </div>
          </div>

        </div>
    </div>
@endsection