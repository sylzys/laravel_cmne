@extends(backpack_view('blank'))
@php
$entry = $crud->entry;
$pic = Storage::disk('users')->url($entry->picture);
@endphp

@section('content')
<div class="container">
    <div class="main-body">
          <div class="row gutters-sm">
            <div class="col-md-4 mb-3">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex flex-column align-items-center text-center">
                  <img src="{{ $entry->picture }}" height=150 widht=150 alt="">
                  <div class="mt-3">
                      <h4>{{$entry->getFullname()}}</h4>
                      <p class="text-muted font-size-sm">{{$entry->address}}</p>
                      <button class="btn btn-outline-primary">Message</button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card mt-3">
                <ul class="list-group list-group-flush">
                  <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                    <h6 class="mb-0">
                    <i class="las la-phone"></i><span class="text"> {{$entry->phone ?? '01-02-03-04-05'}}</span>
                  </li>
                  <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                    <h6 class="mb-0">
                    <i class="las la-at"></i><span class="text"> {{$entry->email}}</span>
                  </li>
                  <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                    <h6 class="mb-0">
                    <i class="las la-map-marker-alt "></i><span class="text"> {{$entry->city ?? 'Paris'}}</span>
                  </li>
                </ul>
              </div>
            </div>
            <div class="col-md-8">

              <div class="row gutters-sm">
              <div class="col-sm-6 mb-3">
                  <div class="card h-100">
                    <div class="card-body">
                      <h3>Dernières quittances</h3>
                      @for($i = 0; $i < 6; $i++)
                        <div class="d-flex flex-row align-items-center">
                          <div class="icon-rounded-primary icon-rounded-md">
                            <i class="las la-file-invoice-dollar"></i>
                          </div>
                          <div class="ms-2 c-details">
                            <h6 class="mb-0">&nbsp; 0{{$i}}-2023</h6>
                          </div>
                        </div>
                      @endfor
                      </div>
                  </div>
                </div>
                <div class="col-sm-6 mb-3">
                  <div class="card h-100">
                    <div class="card-body">
                    </div>
                  </div>
                </div>
              </div>



            </div>
          </div>

        </div>
    </div>
@endsection