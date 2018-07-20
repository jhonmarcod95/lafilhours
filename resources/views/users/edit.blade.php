@extends('layouts.app')

@section('content')


<div class="content">

  <div class="container-fluid">
	 
    <!-- Breadcrumbs -->
    <div class="card mb-3">
    </div>


    <!-- Example Tables Card -->
    <div class="card mb-3">
      <div class="card-header">
        Update an Account
      </div>
		<div class="card-body">
			<form class="form-horizontal" role="form" method="POST" action="{{ url('users/update') }}">
			{{ csrf_field() }}

				<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
				    <label for="name" class="col-md-4 control-label">Name</label>

				    <div class="col-md-6">
				        <input id="name" type="text" class="form-control" name="name" value="{{ Auth::user()->name }}" required autofocus disabled>
				    </div>
				</div>

				<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
				    <label for="email" class="col-md-4 control-label">E-Mail Address</label>

				    <div class="col-md-6">
				        <input id="email" type="email" class="form-control" name="email" value="{{ Auth::user()->email }}" required disabled>
				    </div>
				</div>

				<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
				    <label for="password" class="col-md-4 control-label">Password</label>

				    <div class="col-md-6">
				        <input id="password" type="password" class="form-control" name="password" required>

				        @if ($errors->has('password'))
				            <span class="help-block text-danger">
				                <strong>{{ $errors->first('password') }}</strong>
				            </span>
				        @endif
				    </div>
				</div>

				<div class="form-group">
				    <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

				    <div class="col-md-6">
				        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
				    </div>
				</div>

				<div class="form-group">
				    <div class="col-md-6 col-md-offset-4">
				        <button type="submit" class="btn btn-primary">
				            Update
				        </button>
				    </div>
				</div>
				
				@if(Session::has('flash_message'))
				    <div class="alert alert-success">
				    	<span class="glyphicon glyphicon-ok"></span>{!! session('flash_message') !!}
				    </div>
				@endif
			</form>
		</div>
     </div>
  </div>
</div>

@endsection