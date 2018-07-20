<?php
use App\Methods\Func;
?>

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
      <li class="breadcrumb-item active">Schedules</li>
    
    </ol>

    <!-- Example Tables Card -->
    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-user"></i>&nbsp;
        <b>{{ $employees[0]->employee_name }}</b>
      </div>
      <form action="{{ url('/employees/'.$employees[0]->Userid ) }}" method="POST">
      {{ csrf_field() }}
        <div class="card-body">
          <div class="row">
            <div class="col-xl-2 col-sm-6 mb-2">
              <label>
                Start Date
              </label>   
              {{ Form::date('start_date', date('Y-m-d'), ['class' => 'form-control input-sm']) }}
            </div>
            <div class="col-xl-2 col-sm-6 mb-2">
              <label>
                End Date
              </label>  
              {{ Form::date('end_date', date('Y-m-d'), ['class' => 'form-control input-sm']) }}
            </div>
            <div class="col-xl-2 col-sm-6 mb-2">
              <label>
                Start Time
              </label>
              {{ Form::time('start_time', '08:00', ['class' => 'form-control input-sm']) }}
            </div>
            <div class="col-xl-2 col-sm-6 mb-2">
              <label>
                End Time
              </label>
              {{ Form::time('end_time', '18:00', ['class' => 'form-control input-sm']) }}
            </div>
            <div class="col-xl-2 col-sm-6 mb-2">
              <label>
                Remarks
              </label>
              {{ Form::text('remarks', '', ['class' => 'form-control input-sm', 'placeholder' => 'Input some text here...']) }}
            </div>
          </div>      
          
          <!-- DAYS -->
          <div class="row">
            <div class="col-xl-2 col-sm-6 mb-2">
              Working Days
            </div>
          </div>

          <div class="row">
            
            <div class="col-xl-1 col-sm-6 mb-2">
              <label class="form-check-label">
                <input name="Mon" class="form-check-input" type="checkbox" value="1"> Mon.
              </label>
            </div>
            <div class="col-xl-1 col-sm-6 mb-2">
              <label class="form-check-label">
                <input name="Tue" class="form-check-input" type="checkbox" value="2"> Tue.
              </label>
            </div>
            <div class="col-xl-1 col-sm-6 mb-2">
              <label class="form-check-label">
                <input name="Wed" class="form-check-input" type="checkbox" value="3"> Wed.
              </label>
            </div>
            <div class="col-xl-1 col-sm-6 mb-2">
              <label class="form-check-label">
                <input name="Thurs" class="form-check-input" type="checkbox" value="4"> Thurs.
              </label>
            </div>
            <div class="col-xl-1 col-sm-6 mb-2">
              <label class="form-check-label">
                <input name="Fri" class="form-check-input" type="checkbox" value="5"> Fri.
              </label>
            </div>
            <div class="col-xl-1 col-sm-6 mb-2">
              <label class="form-check-label">
                <input name="Sat" class="form-check-input" type="checkbox" value="6"> Sat.
              </label>
            </div>
            <div class="col-xl-1 col-sm-6 mb-2">
              <label class="form-check-label">
                <input name="Sun" class="form-check-input" type="checkbox" value="7"> Sun.
              </label>
            </div>
            
          </div>

          @if(Auth::privelege()->schedule_control)
          <div class="row">
            <div class="col-xl-2 col-sm-6 mb-2">
              {{ Form::submit('Add Schedule', ['class' => 'btn btn-primary']) }}
            </div>
          </div>
          @endif
        </div>

        <!-- Message Status -->
        <div class="col-xl col-sm mb">
            @include('layouts.errors')
            @if(Session::has('flash_message'))
              <div class="{!! session('flash_font') !!}">
                <span class="glyphicon glyphicon-ok"></span>{!! session('flash_message') !!}
              </div>
            @endif
        </div>
        <!--  -->
        
       </div>
     </form>

     <!-- SCHED TABLE -->
     <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-table"></i>&nbsp;
        Schedule History
      </div>
      <div class="card-body">
          <table id="dataTable" style="font-size:14px;" class="table table-bordered" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>Sched Id
                <th>Start Date
                <th>End Date
                <th>Start Time
                <th>End Time
                <th>Working Days
                <th>Remarks
                <th>Created At
                <th>Updated At
                @if(Auth::privelege()->schedule_control)
                <th>
                <th>
                @endif  
              </tr>
            </thead>
            <tbody>
            @foreach($employees as $employee)
              <tr>
                <td>{{ $employee->id }}
                <td>{{ $employee->start_date }}
                <td>{{ $employee->end_date }}
                <td>{{ func::toTime($employee->start_time) }}
                <td>{{ func::toTime($employee->end_time) }}
                <td>{{ func::toDays($employee->days) }}
                <td>{{ $employee->remarks }}
                <td>{{ func::toSimple12Date($employee->created_at) }} <br> 
                    <span class="text-muted display-9">Created By : {{ $employee->user_name }}</span>
                <td>{{ func::toSimple12Date($employee->updated_at) }} <br> 
                    <span class="text-muted display-9">Updated By : {{ $employee->updated_by }}</span>

                @if(Auth::privelege()->schedule_control)
                <td><a class="btn btn-info" href="{{ url('/employees/edit/' . $employee->Userid . '/' . $employee->id) }}" >Update</a>
                <td><a class="btn btn-danger" href="#" data-toggle="modal" data-target="#ModalConfirm" onclick="SetAttribute('Formdelete', 'action', '{{ url('/employees/' . $employee->Userid . '/' . $employee->id) }}')" class="text-dark\">Delete</a>
                @endif
              </tr>
            @endforeach
            </tbody>
          </table>
        
      </div>
     </div>


  </div>
</div>
@include('employees.modal.confirm')
@endsection


