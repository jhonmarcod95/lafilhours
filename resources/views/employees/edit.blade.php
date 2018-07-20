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
      <li class="breadcrumb-item"><a href="{{ url('/employees/' . $employees[0]->Userid) }}">Schedules</a></li>
      <li class="breadcrumb-item active">Edit</li>
      <li class="breadcrumb-item active">{{ $employees[0]->employee_name }}</li>
    </ol>

    <!-- Example Tables Card -->
    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-user"></i>&nbsp;
        <b> Schedule ID : {{ $schedule->id }}</b>
      </div>
      <form action="{{ url('/employees/update/' . $schedule->Userid . '/' . $schedule->id) }}" method="POST">
      {{ csrf_field() }}
        <div class="card-body">
          <div class="row">
            <div class="col-xl-2 col-sm-6 mb-2">
              <label>
                Start Date
              </label>   
              {{ Form::date('start_date', $schedule->start_date, ['class' => 'form-control input-sm']) }}
            </div>
            <div class="col-xl-2 col-sm-6 mb-2">
              <label>
                End Date
              </label>  
              {{ Form::date('end_date', $schedule->end_date, ['class' => 'form-control input-sm']) }}
            </div>
            <div class="col-xl-2 col-sm-6 mb-2">
              <label>
                Start Time
              </label>

              {{ Form::time('start_time', Func::to24Time($schedule->start_time), ['class' => 'form-control input-sm']) }}
            </div>
            <div class="col-xl-2 col-sm-6 mb-2">
              <label>
                End Time
              </label>
              {{ Form::time('end_time', Func::to24Time($schedule->end_time), ['class' => 'form-control input-sm']) }}
            </div>
            <div class="col-xl-2 col-sm-6 mb-2">
              <label>
                Remarks
              </label>
              {{ Form::text('remarks', $schedule->remarks, ['class' => 'form-control input-sm', 'placeholder' => 'Input some text here...']) }}
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
                <input name="weekDays[]" class="form-check-input" type="checkbox" value="1" {{ Func::check_day('1', $schedule) }} > Mon.
              </label>
            </div>
            <div class="col-xl-1 col-sm-6 mb-2">
              <label class="form-check-label">
                <input name="weekDays[]" class="form-check-input" type="checkbox" value="2" {{ Func::check_day('2', $schedule) }}> Tue.
              </label>
            </div>
            <div class="col-xl-1 col-sm-6 mb-2">
              <label class="form-check-label">
                <input name="weekDays[]" class="form-check-input" type="checkbox" value="3" {{ Func::check_day('3', $schedule) }}> Wed.
              </label>
            </div>
            <div class="col-xl-1 col-sm-6 mb-2">
              <label class="form-check-label">
                <input name="weekDays[]" class="form-check-input" type="checkbox" value="4" {{ Func::check_day('4', $schedule) }}> Thurs.
              </label>
            </div>
            <div class="col-xl-1 col-sm-6 mb-2">
              <label class="form-check-label">
                <input name="weekDays[]" class="form-check-input" type="checkbox" value="5" {{ Func::check_day('5', $schedule) }}> Fri.
              </label>
            </div>
            <div class="col-xl-1 col-sm-6 mb-2">
              <label class="form-check-label">
                <input name="weekDays[]" class="form-check-input" type="checkbox" value="6" {{ Func::check_day('6', $schedule) }}> Sat.
              </label>
            </div>
            <div class="col-xl-1 col-sm-6 mb-2">
              <label class="form-check-label">
                <input name="weekDays[]" class="form-check-input" type="checkbox" value="7" {{ Func::check_day('7', $schedule) }}> Sun.
              </label>
            </div>
            
          </div>

          <div class="row">
            <div class="col-xl-2 col-sm-6 mb-2">
              {{ Form::submit('Save Changes', ['class' => 'btn btn-success']) }}
            </div>
          </div>
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
                <th>Created by
                <th>Created At

              </tr>
            </thead>
            <tbody>
            @foreach($employees as $employee)
              <tr @if ($schedule->id == $employee->id) class="bg-info text-white" @endif >
                <td>{{ $employee->id }}
                <td>{{ $employee->start_date }}
                <td>{{ $employee->end_date }}
                <td>{{ func::toTime($employee->start_time) }}
                <td>{{ func::toTime($employee->end_time) }}
                <td>{{ func::toDays($employee->days) }}
                <td>{{ $employee->remarks }}
                <td>{{ $employee->user_name }}
                <td>{{ func::toSimple12Date($employee->created_at) }}
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


