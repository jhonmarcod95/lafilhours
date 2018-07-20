
<table class="table table-hover">
  <thead>
    <tr>
      <th>Date</th>
      
      <th>Time In</th>
      <th>Time Out</th>
      <th>Total</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($logs as $log)
    <tr>
      <td> {{ $log->date}}
     
      <td> {{ $log->timein }}
      <td> {{ $log->timeout }}
      <td> {{ $log->total_hours }}
    </tr>
    @endforeach
  </tbody>
</table>
