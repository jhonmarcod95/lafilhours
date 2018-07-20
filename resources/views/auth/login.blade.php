@extends('layouts.app')

@section('content')
<div class="container">
  <div class="card card-login mx-auto mt-5">
    <div class="card-header">
      Login
    </div>
    <div class="card-body">


      <form class="form-horizontal" role="form" method="POST" action="{{ route('login') }}">
        {{ csrf_field() }}
        <div class="form-group">
          <label for="exampleInputEmail1">Email address</label>
          <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
         
          @if ($errors->has('email'))
              <span class="text-danger">
                  {{ $errors->first('email') }}
              </span>
          @endif

        </div>


        <div class="form-group">
          <label for="exampleInputPassword1">Password</label>
          <input id="password" type="password" class="form-control" name="password" required>

          @if ($errors->has('password'))
              <span class="text-danger">
                  <strong>{{ $errors->first('password') }}</strong>
              </span>
          @endif
        </div>

        
        <div class="form-group">
          <div class="form-check">
            <label class="form-check-label">
              <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
            </label>
          </div>
        </div>
        <button type="submit" class="btn btn-primary btn-block">
            Login
        </button>
      
      </form>

    </div>
  </div>
</div>

@endsection
