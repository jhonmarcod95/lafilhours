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
        Register an Account
      </div>
		<div class="card-body">
			<form class="form-horizontal" role="form" method="POST" action="{{ url('users/create') }}">
			{{ csrf_field() }}

				<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
				    <label for="name" class="col-md-4 control-label">Name</label>

				    <div class="col-md-6">
				        <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

				        @if ($errors->has('name'))
				            <span class="help-block">
				                <strong>{{ $errors->first('name') }}</strong>
				            </span>
				        @endif
				    </div>
				</div>

				<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
				    <label for="email" class="col-md-4 control-label">E-Mail Address</label>

				    <div class="col-md-6">
				        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

				        @if ($errors->has('email'))
				            <span class="help-block">
				                <strong>{{ $errors->first('email') }}</strong>
				            </span>
				        @endif
				    </div>
				</div>

				<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
				    <label for="password" class="col-md-4 control-label">Password</label>

				    <div class="col-md-6">
				        <input id="password" type="password" class="form-control" name="password" required>

				        @if ($errors->has('password'))
				            <span class="help-block">
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
				            Register
				        </button>
				    </div>
				</div>
			</form>
		</div>
     </div>
  </div>
</div>

<div class="container-fluid">
	<div class="card mb-3">
		<div class="card-header">
		User Record
		</div>
		<div class="card-body">
			<table id="dataTable" class="table table-bordered" width="100%" cellspacing="0">
        		<thead>
	    			<tr>
	    				<th>Name
    					<th>Email
    					<th>Created At
    					<!-- <th> -->
	    			</tr>
        		</thead>
        		<tbody>
        			@foreach ($users as $user)
        			<tr>
        				<td>{{ $user->name }} 
        				<td>{{ $user->email }} 
    					<td>{{ $user->created_at }} 	
    					<!-- <td><a class="btn btn-danger" href="#" data-toggle="modal">Delete</a> -->
        			</tr>
        			@endforeach
        		</tbody>
        	</table>
		</div>
	</div>
</div>

@endsection