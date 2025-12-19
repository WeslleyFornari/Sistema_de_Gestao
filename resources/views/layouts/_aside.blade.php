<style>
  .sidenav-header {
    display: flex;
    justify-content: center;
    align-items: center;
    height: auto;
  }

  .logo_main {
    width: 80% !important;
    height: 60% !important;
    object-fit: contain;
    filter: brightness(1.09) contrast(1.0);
  }
</style>

<div class="sidenav-header">
  <img src="{{asset('img/logos/logo3.png')}}" class="logo_main" alt="main_logo">
  <span class="ms-1 font-weight-bold"></span>
  </a>
</div>

<hr class="horizontal dark my-3">

<div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
  <ul class="navbar-nav">
    @include('layouts.aside._admin')
  </ul>
</div>