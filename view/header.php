<header class="d-flex flex-column carousel-header">
      <nav class="navbar nav-expand-lg fundo-nav d-grid" 
      aria-label="Menu de navegação">
      <div class="d-flex flex-wrap-reverse justify-content-between mx-1">
        <div class="order-2 d-flex flex-column align-items-center ">
            <p class="titulo">GMF</p>
            <p class="investimentos">Investimentos</p>
        </div>
        <button class="navbar-toggler order-1" type="button" data-bs-toggle="collapse" 
        data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="true" aria-label="Alternar navegação">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="nova-conta text-white order-3" href=<?=$enderecoCadastro?>>Abra sua conta</a>
        <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="d-flex align-items-start navbar-nav ms-auto p-1">
                  <?php foreach($enderecos as $key => $endereco):?>
                      <li class="nav-item p-2">
                          <a class="nav-link text-white" href=<?=$endereco?>><?=$listas[$key]?></a>
                      </li>
                  <?php endforeach ?>
                </ul>
            </div>
        </div>
      </nav>