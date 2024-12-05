<div class="tag-list">
        <div class="fade fade-left"></div>
          <div class="inner">
          <?php foreach ($tickers as $nome => $dados): ?>
            <?php
              $variacao = $carrossel->getVariacao($dados, $nome);
              if($variacao > 0){
                $classVariacao = 'variacaoPositiva';
              }
              elseif($variacao < 0){
                $classVariacao = 'variacaoNegativa';
              }
              else{
                $classVariacao = 'variacaoNeutra';
              }
              $variacaoFormatada = number_format($variacao, 2, ',', '.') . '%';
            ?>
            <div class='tag'><?=$nome?>
                <div class='valor'><?=$carrossel->getValorAtual($dados, $nome)?></div>
                <div class='variacao <?=$classVariacao?>'><?=$variacaoFormatada?></div>
            </div>
          <?php endforeach; ?>
        </div>
        <div class="fade fade-right"></div>
      </div>
</header>