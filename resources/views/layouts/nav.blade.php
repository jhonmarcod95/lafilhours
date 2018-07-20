<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
  <a class="navbar-brand" href="{{ url('/logs') }}"><img style="height:30px" src="{{ asset('image/lafil_logo.png') }}">&nbsp; &nbsp;Time Monitoring</a>
  <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>



  <div class="collapse navbar-collapse" id="navbarResponsive">
    <!-- Authentication Links -->
    @if (Auth::guest())
    @else
    <ul class="navbar-nav ml-auto">

      

      @if(Auth::privelege()->registration_access)
      <li class="nav-item">
        <a class="nav-link" href="{{ url('/users') }}">
          <i class="fa fa-user-plus"></i>&nbsp;Users
        </a>
      </li>
      @endif

      <li class="nav-item">
        <a class="nav-link" href="{{ url('/logs') }}">
          <i class="fa fa-calendar"></i>&nbsp;Logs
        </a>
      </li>

      @if(Auth::privelege()->schedule_access)
      <li class="nav-item">
        <a class="nav-link" href="{{ url('/employees') }}">
          <i class="fa fa-users"></i>&nbsp;Workers
        </a>
      </li>
      @endif

      @if(Auth::privelege()->attendance_access)
      <li class="nav-item">
        <a class="nav-link" href="{{ url('/attendance') }}">
          <i class="fa fa-bar-chart"></i>&nbsp;Attendance
        </a>
      </li>
      @endif


      <li class="navbar-nav ml-auto">
        <a class="nav-link" href="{{ url('/users/edit') }}">
          <i class="fa fa-user"></i>&nbsp;{{ Auth::user()->name }}
        </a>
      </li>

      <li class="navbar-nav ml-auto">
        <a class="nav-link" href="{{ route('logout') }}"
            onclick="event.preventDefault();
                     document.getElementById('logout-form').submit();">
            <i class="fa fa-sign-out"></i>
            Logout
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
      </li>
    </ul>

    @endif

  </div>

</nav>
