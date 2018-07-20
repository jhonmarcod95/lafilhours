@extends('layouts.app')


@section('content')

<div class="content">

  <div class="container-fluid">
   
    <!-- Breadcrumbs -->
    <div class="card mb-3">
    </div>
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="{{ url('/employees') }}">Employees</a>
      </li>
    </ol>

    <!-- LOCATION FILTER -->
    <div class="card mb-3">
      <div class="card-body">
        <div class="table-responsive">
          <form class="form-inline">
            <div class="form-group mb-2">
              <label for="exampleFormControlSelect1">Location : </label>
            </div>
            <div class="form-group mx-sm-3 mb-2">
              {{ Form::select('device', $devices, '', ['class' => 'form-control']) }}
            </div>
            <div class="form-group mx-sm-3 mb-2">
              <button type="submit" class="btn btn-primary fa fa-search"></button>      
            </div>
          </form>
        </div>
      </div>
     </div>

    

    <!-- Example Tables Card -->
    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-users"></i>
      </div>

        <div class="card-body">
          <form action="{{ url('/createAll') }}" method="POST">
    
          <div class="row">

           
            {{ csrf_field() }}
            <div class="col-sm-3">
              <div class="mb-2">
                <label>
                  Start Date
                </label>   
                {{ Form::date('start_date', date('Y-m-d'), ['class' => 'form-control input-sm']) }}
              </div>
              <div class="mb-2">
                <label>
                  End Date
                </label>  
                {{ Form::date('end_date', date('Y-m-d'), ['class' => 'form-control input-sm']) }}
              </div>
              <div class="mb-2">
                <label>
                  Start Time
                </label>
                {{ Form::time('start_time', '08:00', ['class' => 'form-control input-sm']) }}
              </div>
              <div class="mb-2">
                <label>
                  End Time
                </label>
                {{ Form::time('end_time', '18:00', ['class' => 'form-control input-sm']) }}
              </div>
              <div class="mb-2">
                <label>
                  Remarks
                </label>
                {{ Form::text('remarks', '', ['class' => 'form-control input-sm', 'placeholder' => 'Input some text here...']) }}
              </div>
              
               <!-- DAYS -->
              <div class="row">
                <div class="col mb-2">
                  Working Days
                </div>
              </div>

              <div class="row">
                <div class="col-sm-3 mb-2">
                  <label class="form-check-label">
                    <input name="weekDays[]" class="form-check-input" type="checkbox" value="1"> Mon.
                  </label>
                </div>
                <div class="col-sm-3 mb-2">
                  <label class="form-check-label">
                    <input name="weekDays[]" class="form-check-input" type="checkbox" value="2"> Tue.
                  </label>
                </div>
                <div class="col-sm-3 mb-2">
                  <label class="form-check-label">
                    <input name="weekDays[]" class="form-check-input" type="checkbox" value="3"> Wed.
                  </label>
                </div>
                <div class="col-sm-3 mb-2">
                  <label class="form-check-label">
                    <input name="weekDays[]" class="form-check-input" type="checkbox" value="4"> Thurs.
                  </label>
                </div>
                <div class="col-sm-3 mb-2">
                  <label class="form-check-label">
                    <input name="weekDays[]" class="form-check-input" type="checkbox" value="5"> Fri.
                  </label>
                </div>
                <div class="col-sm-3 mb-2">
                  <label class="form-check-label">
                    <input name="weekDays[]" class="form-check-input" type="checkbox" value="6"> Sat.
                  </label>
                </div>
                <div class="col-sm-3 mb-2">
                  <label class="form-check-label">
                    <input name="weekDays[]" class="form-check-input" type="checkbox" value="7"> Sun.
                  </label>
                </div>
                
              </div>    

              <div id="AddSched" class="mb-2">
                {{ Form::submit('Add Schedule', ['class' => 'btn btn-primary']) }}
              </div>          

              <!-- Message Status -->
              <div class="mb-2">
                @include('layouts.errors')
                @if(Session::has('flash_message'))
                  <div class="{!! session('flash_font') !!}">
                    <span class="glyphicon glyphicon-ok"></span>{!! session('flash_message') !!}
                  </div>
                @endif
              </div>

              <!--  -->
            </div>

            <div class="col">
              <div class="card-body">
                <div class="table-responsive">
                  <table id="dataTable" class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                      <th><!-- <input type="checkbox" onClick="checkAll(this,'chk')"> -->
                      <th>Name
                      <th>Location
                      <th>
                    </tr>
                    </thead>
                    <tbody>
                      @foreach ($employees as $employee)
                      <tr>
                        <td style="width:10px"><input id="chk" name="checkitems[]" type="checkbox" value="{{ $employee->Userid }} ">
                        <td>{{ $employee->Name }} 
                        <td>{{ $employee->Location }} 
                        <td><a href='{{ url("/employees/$employee->Userid") }}' class="btn btn-info">Show Schedule</a>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          

        </div>  
        </form>
      
     </div>


  </div>
</div>


@endsection
