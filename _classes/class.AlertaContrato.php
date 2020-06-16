<?php

/**
 * Cria um alerta
 *
 * @author alat
 */
class AlertaContrato
{
    ##############################################################

    public function __construct($idContrato)
    {
        # Conecta ao banco
        $contratos = new Contratos();
        
        # Inicia a variável de erro
        $erro = array();
        
        ################################################################################
                
        # Verifica se tem menos de 3 membros da comissão de fiscalização
        $select = "SELECT idComissao
                     FROM tbcomissao 
                    WHERE idContrato = {$idContrato} 
                      AND dtPublicacaoSaida IS NULL";
                    
        $membros = $contratos->count($select);
        
        # Verifica se é menor que 3 (Contrário a legislação)
        if(($membros < 3) AND ($membros > 0)){
            $erro[] = "De acordo com a legislação vigente, a comissão de fiscalização deverá ter, pelo menos, 3 (TRÊS) membros ativos!";
        }

        # Verifica se tem comissão de fiscalização
        if($membros == 0){
            $erro[] = "É necessário designar uma comissão de fiscalização para esse contrato !!";
        }
        
        ################################################################################
        
        # Verifica se tem 1 presidente
        $select = "SELECT idComissao
                     FROM tbcomissao 
                    WHERE idContrato = {$idContrato} 
                      AND dtPublicacaoSaida IS NULL
                      AND tipo = 1";
                    
        $presidente = $contratos->count($select);
        
        if($membros > 0){
            # Verifica se tem 1 presidente
            if($presidente > 1){
                $erro[] = "A comissão deve ter SOMENTE 1 presidente!";
            }

            if(($presidente < 1) OR (empty($presidente))){
                $erro[] = "A comissão deve ter, ao menos, 1 presidente!";
            }
        }
        
        ################################################################################
                
        # Verifica se a data da assinatura combina com a do número do contrato
        $select = "SELECT numero, 
                          YEAR(dtAssinatura)
                     FROM tbcontrato 
                    WHERE idContrato = {$idContrato}";
                    
        $conteudo = $contratos->select($select, false);

        $numero = $conteudo[0];

        if(!empty($conteudo[1])){
            $partes = explode("/",$numero);

            if($conteudo[1] <> $partes[1]){
                $erro[] = "O ano de assinatura ({$conteudo[1]}) está diferente do ano do número do contrato ({$partes[1]})!! Favor alterar!";
            }
        }
        
        ################################################################################
        
        # Exibe o erro (se tiver)
        if(count($erro) == 0){
            return;
        }else{
            $painel = new Callout("alert");
            $painel->abre();
            
            $grid = new Grid();
            $grid->abreColuna(1);
            
            $figura = new Imagem(PASTA_FIGURAS_GERAIS . 'aviso.png', 'Imagem demonstrativa', 50, 50);
            $figura->show();
            
            $grid->fechaColuna();
            $grid->abreColuna(11);             

            p("ATENÇÂO !!","palerta");
            
            foreach($erro as $item){            
                p($item,"palerta");                
            }
            
            $grid->fechaColuna();
            $grid->fechaGrid();
            $painel->fecha();
        }
    }

    ##############################################################
    
}