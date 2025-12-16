
<div class="row mt-5 mb-1 justify-content-center" style="margin-left:1px;">
        <div class="col-12 col-sm-3 mb-4">


          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Clientes</p>
                    <h5 class="font-weight-bolder mb-0">
                       {{$clientes_qtd}} 
                      <span class="text-success text-sm font-weight-bolder"></span>
                    </h5>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-light shadow text-center border-radius-md">
                    <i class="fas fa-user" style=" font-size:25px;" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>



        <div class="col-12 col-sm-4 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Pedidos</p>
                    <h5 class="font-weight-bolder mb-0">
                    {{$pedidos_qtd}}
                      <span class="text-danger text-sm font-weight-bolder"></span>
                    </h5>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-light shadow text-center border-radius-md">
                  <i class="fas fa-shopping-cart" style="font-size:25px;"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12 col-sm-5 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Faturamento</p>
                    <h5 class="font-weight-bolder mb-0">
                   R${{ getMoney($faturamento)}}
                      <span class="text-success text-sm font-weight-bolder"></span>
                    </h5>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-light shadow text-center border-radius-md">
                  <i class="fas fa-file-invoice-dollar" style="font-size: 25px;" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
</div>