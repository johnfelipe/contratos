<?php

/**
 * Cadastro de Campus
 *  
 * By Alat
 */
# Reservado para o servidor logado
$idUsuario = null;

# Configuração
include ("_config.php");

# Permissão de Acesso
$acesso = Verifica::acesso($idUsuario, 9);

if ($acesso) {
    # Conecta ao Banco de Dados
    $intra    = new Intra();
    $contrato = new Contrato();
    $pessoal  = new Pessoal();
    $comissao = new Comissao();
    $aditivo  = new Aditivo();

    # Verifica a fase do programa
    $fase = get("fase", "listar");

    # pega o id (se tiver)
    $id = soNumeros(get("id"));

    # pega o contrato
    $idContrato = soNumeros(get_session('sessionContrato'));

    # Começa uma nova página
    $page = new Page();
    $page->iniciaPagina();

    # Cabeçalho da Página
    AreaServidor::cabecalho();

    # Abre um novo objeto Modelo
    $objeto = new Modelo();

    ################################################################
    # Exibe os dados do Servidor
    $objeto->set_rotinaExtra("get_DadosContrato");
    $objeto->set_rotinaExtraParametro($idContrato);

    # Nome do Modelo
    $objeto->set_nome('Aditivos');

    # Botão de voltar da lista
    $objeto->set_voltarLista('areaContrato.php');

    # select da lista
    $objeto->set_selectLista("SELECT idAditivo,
                                     objeto,
                                     idAditivo,
                                     dtAssinatura,                                     
                                     idAditivo,
                                     idAditivo,
                                     idAditivo
                                FROM tbaditivo
                               WHERE idContrato = {$idContrato}
                            ORDER BY dtAssinatura");

    # select do edita
    $objeto->set_selectEdita('SELECT tipo,
                                     objeto,
                                     dtAssinatura,
                                     dtPublicacao,
                                     pgPublicacao,
                                     valor,
                                     garantia,                                   
                                     dtInicial,
                                     prazo,
                                     tipoPrazo,
                                     obs,
                                     idContrato,
                                     idAditivo
                                FROM tbaditivo
                              WHERE idAditivo = ' . $id);

    # Caminhos
    $objeto->set_linkEditar('?fase=editar');
    $objeto->set_linkExcluir('?fase=excluir');
    $objeto->set_linkGravar('?fase=gravar');
    $objeto->set_linkListar('?fase=listar');

    # Parametros da tabela
    $objeto->set_label(array("Tipo", "Objeto", "Publicação", "Assinatura", "Duração", "Garantia", "Valor"));
    $objeto->set_align(array("center", "left", "center"));
    $objeto->set_width(array(10, 25, 10, 10, 10, 15, 13));
    $objeto->set_classe(array("Aditivo", null, "Aditivo", null, "Aditivo", "Aditivo", "Aditivo"));
    $objeto->set_metodo(array("getTipoNumerado", null, "getPublicacao", null, "getPeriodo", "getGarantia", "getValor"));
    $objeto->set_funcao(array(null, null, null, "date_to_php"));

    # Classe do banco de dados
    $objeto->set_classBd('Contratos');

    # Nome da tabela
    $objeto->set_tabela('tbaditivo');

    # Nome do campo id
    $objeto->set_idCampo('idAditivo');

    # Dados da combo tipo
    $tipo = array(
        array(null, null),
        array(1, "Dias"),
        array(2, "Meses")
    );

    # Campos para o formulario
    $objeto->set_campos(array(
        array(
            'linha'    => 1,
            'nome'     => 'tipo',
            'label'    => 'Tipo:',
            'tipo'     => 'combo',
            'required' => true,
            'array'    => array([1, "Aditivo"], [2, "Apostila"]),
            'col'      => 3,
            'size'     => 15),
        array(
            'linha' => 1,
            'nome'  => 'objeto',
            'label' => 'Objeto:',
            'tipo'  => 'texto',
            'col'   => 9,
            'size'  => 250),
        array(
            'linha'    => 2,
            'nome'     => 'dtAssinatura',
            'label'    => 'Assinatura:',
            'required' => true,
            'tipo'     => 'date',
            'col'      => 3,
            'size'     => 15),
        array(
            'linha' => 2,
            'nome'  => 'dtPublicacao',
            'label' => 'Publicação:',
            'tipo'  => 'date',
            'col'   => 3,
            'size'  => 15),
        array(
            'linha' => 2,
            'nome'  => 'pgPublicacao',
            'label' => 'Pag:',
            'tipo'  => 'texto',
            'col'   => 2,
            'size'  => 10),
        array(
            'linha' => 3,
            'nome'  => 'valor',
            'label' => 'Valor: (se houver)',
            'tipo'  => 'moeda',
            'col'   => 3,
            'size'  => 15),
        array(
            'linha' => 3,
            'nome'  => 'garantia',
            'label' => 'Garantia: (se houver)',
            'tipo'  => 'percentagem',
            'col'   => 2,
            'size'  => 5),
        array(
            'linha'  => 5,
            'nome'   => 'dtInicial',
            'label'  => 'Data Inicial:',
            'tipo'   => 'date',
            'col'    => 3,
            'padrao' => date_to_bd($aditivo->getDataInicialNovoAditivo($idContrato)),
            'size'   => 15),
        array(
            'linha' => 5,
            'nome'  => 'prazo',
            'label' => 'Prazo:',
            'tipo'  => 'texto',
            'col'   => 2,
            'size'  => 15),
        array(
            'linha' => 5,
            'nome'  => 'tipoPrazo',
            'label' => 'Tipo:',
            'tipo'  => 'combo',
            'array' => $tipo,
            'col'   => 2,
            'size'  => 15),
        array(
            'linha' => 4,
            'nome'  => 'obs',
            'label' => 'Observação:',
            'tipo'  => 'textarea',
            'size'  => array(80, 3)),
        array(
            "linha"  => 5,
            "nome"   => "idContrato",
            "label"  => "idContrato:",
            'tipo'   => 'hidden',
            'padrao' => $idContrato,
            "col"    => 3,
            "size"   => 11)
    ));

    # idUsuário para o Log
    $objeto->set_idUsuario($idUsuario);

    ################################################################
    switch ($fase) {
        case "" :
        case "listar" :
            $objeto->$fase();
            break;
        case "editar" :
        case "excluir" :
            $objeto->$fase($id);
            break;
        case "gravar" :
            $objeto->gravar($id,"cadastroAditivoExtra.php");
            break;
    }

    $page->terminaPagina();
} else {
    loadPage("../../areaServidor/sistema/login.php");
}