@extends('layouts.admin')
@section('content')
	{{-- @include('partials.breadcrumb') --}}
	@include('partials.subheader')
	{{-- @can('online_access') --}}
		@include('partials.sysalert')

@endsection

@section('scripts')
	@parent
	@endsection
