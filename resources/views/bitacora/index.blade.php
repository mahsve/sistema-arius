@extends('plantilla')

@section('title', 'Bitácora - ' . env('TITLE'))

@section('styles')
@endsection

@section('scripts')
@endsection

@section('content')
<div class="mb-3">
	<div class="row align-items-center">
		<div class="col-6 text-start">
			<h4 class="card-title text-uppercase my-2"><i class="fas fa-users"></i> Bitácora</h4>
		</div>
	</div>
</div>
@endsection