<?php 
use App\Methods\Func;
?>

<!-- <table id="dataTable" class="table table-bordered" width="100%" cellspacing="0"> -->
<table id="" class="table table-bordered" width="100%" cellspacing="0">
  <thead>
    <tr>
      <th>Date</th>
      <th>Name</th>
      <th>Time In</th>
      <th>Sensor IN</th>
      <th>Time Out</th>
      <th>Sensor Out</th>
      <th>Total</th>
    </tr>
  </thead>
  <tbody>
  @if(count($logs) > 0) 
    @foreach ($logs as $log)
      <tr>
        <td> {{ Func::toLongDate($log->date) }}
        <td> {{ $log->employee_name }}
        <td> <span class="text-muted" style="font-size: 12px;"> {{ Func::toLongDate($log->timein) }} </span> <br> {{ Func::toTime($log->timein) }}
        <td><font size="1"> {{ $log->sensorin }}
        <td> <span class="text-muted" style="font-size: 12px;"> {{ Func::toLongDate($log->timeout) }} </span> <br> {{ Func::toTime($log->timeout) }}
        <td><font size="1">  {{ $log->sensorout }}
        <td> {{ $log->total_hours }}
      </tr>
    @endforeach
  @else  
    <tr>
      <td class="text-center text-danger" colspan="5"> - No Record Found -
    </tr>
  @endif

  </tbody>
</table>

