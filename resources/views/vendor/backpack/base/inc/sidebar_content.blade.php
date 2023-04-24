{{-- This file is used to store sidebar items, inside the Backpack admin panel --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>
<!-- @if(isAdmin()) -->
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('setting') }}'><i class='nav-icon la la-cog'></i> <span>Settings</span></a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('user') }}"><i class="nav-icon la la-users-cog"></i> Users</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('building') }}"><i class="nav-icon la la-building"></i> Résidences</a></li>
<!-- @endif -->
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('housing') }}"><i class="nav-icon la la-question"></i> Housings</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('residence') }}"><i class="nav-icon la la-question"></i> Residences</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('amenity') }}"><i class="nav-icon la la-question"></i> Amenities</a></li>