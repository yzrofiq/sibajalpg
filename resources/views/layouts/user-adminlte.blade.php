@extends('layouts.adminlte')

{{-- ✅ Ini yang bikin sidebar admin hilang --}}
@section('hideSidebar', 'true')

{{-- Gantikan navbar default dengan navbar user --}}
@section('navbar')
  @include('layouts.partials.user-navbar')
  @yield('navbar-extra')
@endsection

{{-- Gantikan footer default dengan footer user --}}
@section('footer')
  @include('layouts.partials.user-footer')
@endsection
