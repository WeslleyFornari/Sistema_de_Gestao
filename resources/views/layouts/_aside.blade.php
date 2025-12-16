<div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href="{{route('app.dash.index')}}">
    
        <img src="{{asset('img/logos/logo-menu.png')}}" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold"></span>
      </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
      <ul class="navbar-nav">
      @include('layouts.aside._'.Auth::user()->role)
      </ul>
    </div>
    