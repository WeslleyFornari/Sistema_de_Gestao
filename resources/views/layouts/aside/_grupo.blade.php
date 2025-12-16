<li class="nav-item">
  <a class="nav-link  {{ request()->routeIs('app.dash.*') ? 'active' : '' }}" href="{{route('app.dash.index')}}">
  <div class="icon icon-shop icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
  <i class="fa-solid fa-chart-line"></i>
    </div>
    <span class="nav-link-text ms-1">Dashboard</span>
  </a>
</li>

<li class="nav-item">
  <a class="nav-link  {{ request()->routeIs('app.categorias.*') ? 'active' : '' }}" href="{{route('app.categorias.index')}}">
    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
      <svg width="12px" height="12px" viewBox="0 0 43 36" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
        <title>credit-card</title>
        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
          <g transform="translate(-2169.000000, -745.000000)" fill="#FFFFFF" fill-rule="nonzero">
            <g transform="translate(1716.000000, 291.000000)">
              <g transform="translate(453.000000, 454.000000)">
                <path class="color-background opacity-6" d="M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z"></path>
                <path class="color-background" d="M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z"></path>
              </g>
            </g>
          </g>
        </g>
      </svg>
    </div>
    <span class="nav-link-text ms-1">Categorias</span>
  </a>
</li>


<li class="nav-item">
  <a class="nav-link  {{ request()->routeIs('app.produtos.*') ? 'active' : '' }}" href="{{route('app.produtos.index')}}">
    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
    <i class="fa-solid fa-box"></i>
    </div>
    <span class="nav-link-text ms-1">Produtos</span>
  </a>
</li>

<li class="nav-item">
  <a class="nav-link  {{ request()->routeIs('app.pagamentos.*') ? 'active' : '' }}" href="{{route('app.pagamentos.index')}}">
    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
    <i class="fa-solid fa-file-invoice-dollar"></i>
    </div>
    <span class="nav-link-text ms-1">Pagamentos</span>
  </a>
</li>

<br>

<li class="nav-item">
  <a class="nav-link  {{ request()->routeIs('app.fluxo-caixa.lancamentos.*') ? 'active' : '' }}" href="{{route('app.fluxo-caixa.lancamentos.index')}}">
    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
    <i class="fa-solid fa-clipboard"></i>
    </div>
    <span class="nav-link-text ms-1">Extratos</span>
  </a>
</li>

<li class="nav-item">
  <a class="nav-link  {{ request()->routeIs('app.fluxo-caixa.contas-pagar.*') ? 'active' : '' }}" href="{{route('app.fluxo-caixa.contas-pagar.index')}}">
    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
    <i class="fa-solid fa-arrow-up-from-bracket"></i>
    </div>
    <span class="nav-link-text ms-1">Contas a pagar</span>
  </a>
</li>

<li class="nav-item">
  <a class="nav-link  {{ request()->routeIs('app.fluxo-caixa.contas-receber.*') ? 'active' : '' }}" href="{{route('app.fluxo-caixa.contas-receber.index')}}">
    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
    <i class="fa-solid fa-keyboard"></i>
    </div>
    <span class="nav-link-text ms-1">Contas a receber</span>
  </a>
</li>

<li class="nav-item">
  <a class="nav-link  {{ request()->routeIs('app.fluxo-caixa.categorias.*') ? 'active' : '' }}" href="{{route('app.fluxo-caixa.categorias.index')}}">
    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
         <i class="fa-solid fa-layer-group"></i>
    </div>
    <span class="nav-link-text ms-1">Categorias da Conta</span>
  </a>
</li>

<li class="nav-item">
  <a class="nav-link  {{ request()->routeIs('app.fluxo-caixa.contas.*') ? 'active' : '' }}" href="{{route('app.fluxo-caixa.contas.index')}}">
    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
    <i class="fa-solid fa-file-invoice"></i>
    </div>
    <span class="nav-link-text ms-1">Contas</span>
  </a>
</li>