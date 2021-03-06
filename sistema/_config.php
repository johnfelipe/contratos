<?php

/**
 * Configuração do Sistema de Pessoal
 * 
 * By Alat
 */
header("Content-Type: text/html; charset=utf-8");


/**
 * [ PHP Basic Config ] Configurações basicas do sistema
 * Configura o timezone da aplicação
 * Define a função para output de erros.
 */
date_default_timezone_set("America/Sao_Paulo");
set_error_handler("fullStackPHPErrorHandler");

/**
 * [ php config ] Altera modo de erro e exibição do var_dump.
 * display_errors: Erros devem ser exibidos.
 * error_reporting: Todos os tipos de erros
 * overload_var_dump: Omitir a linha de caminho do var_dump.
 */
ini_set("display_errors", 1);
ini_set("error_reporting", E_ALL);
ini_set('xdebug.overload_var_dump', 1);

/**
 * [ Default errors ] Função para exibir erros do PHP
 */
function fullStackPHPErrorHandler($error, $message, $file, $line)
{
    $color = ($error == E_USER_ERROR ? "red" : "yellow");
    echo "<div class='trigger' style='border-color: var(--{$color}); color:var(--{$color});'>[ Linha {$line} ] {$message}<small>{$file}</small></div>";
}

/*
 *  Classes
 */
define("PASTA_CLASSES_GERAIS", "../../_framework/_classesGerais/");  // Classes Gerais
define("PASTA_CLASSES_ADMIN", "../../areaServidor/_classes/");       // Classes do sistema de Administração 
define("PASTA_CLASSES_GRH", "../../grh/_classes/");                  // Classes do sistema de GRH
define("PASTA_CLASSES", "../_classes/");                             // Classes Específicas

/*
 *  Funções
 */
define("PASTA_FUNCOES_GERAIS", "../../_framework/_funcoesGerais/");  // Funções Gerais
define("PASTA_FUNCOES", "../_funcoes/");                             // Funções Específicas

/*
 *  Figuras
 */
define("PASTA_FIGURAS_GERAIS", "../../_framework/_imgGerais/");      // Figuras Gerais
define("PASTA_FIGURAS", "../_img/");                                 // Figuras Específicas

/*
 *  Estilos
 */
define("PASTA_ESTILOS_GERAIS", "../../_framework/_cssGerais/");      // Estilos Gerais (Foundation)
define("PASTA_ESTILOS", "../_css/");                                 // Estilos Específicos

/*
 *  Arquivos
 */
define("PASTA_FOTOS", "../../_arquivos/fotos/");                    // Fotos dos Servidores
define("PASTA_CONTRATOS", "../../_arquivos/contratos/");            // Publicação de Contratos
define("PASTA_ADITIVOS", "../../_arquivos/contratosAditivos/");     // Publicação de Aditivos
define("PASTA_DOCUMENTOS", "../../_arquivos/contratosDocumentos/"); // Documentos extras

/*
 *  Tags aceitas em campos com htmlTag = true
 */
define('TAGS', '<p></p><a></a><br/><br><div></div><table></table><tr></tr><td></td><th></th><strong></strong><em></em><u></u><sub></sub><sup></sup><ol></ol><li></li><ul></ul><hr><span></span><h3></h3>');

# Cria array dos meses
$mes = array(array("1", "Janeiro"),
    array("2", "Fevereiro"),
    array("3", "Março"),
    array("4", "Abril"),
    array("5", "Maio"),
    array("6", "Junho"),
    array("7", "Julho"),
    array("8", "Agosto"),
    array("9", "Setembro"),
    array("10", "Outubro"),
    array("11", "Novembro"),
    array("12", "Dezembro"));

$nomeMes = array(null,
    "Janeiro",
    "Fevereiro",
    "Março",
    "Abril",
    "Maio",
    "Junho",
    "Julho",
    "Agosto",
    "Setembro",
    "Outubro",
    "Novembro",
    "Dezembro");

# Inicia a Session
session_start([
    'cookie_lifetime' => 60 * 60 * 8,   // Vida do cookie que armazena a sessao (8h)
]);

# Funçõess gerais	
include_once (PASTA_FUNCOES_GERAIS . "funcoes.gerais.php");
include_once (PASTA_FUNCOES . "funcoes.especificas.php");

# Framework gráfico 
#include ('../../_framework/_outros/libchart/classes/libchart.php');
# Dados do Browser
$browser = get_BrowserName();
define("BROWSER_NAME", $browser['browser']); # Nome do browser
define("BROWSER_VERSION", $browser['version']); # Versão do browser
# Pega o ip e nome da máquina
define("IP", getenv("REMOTE_ADDR"));     # Ip da máquina
# Sistema Operacional
define("SO", get_So());

# carrega as session
$idUsuario            = get_session('idUsuario');                          // Servidor Logado
$idServidorPesquisado = get_session('idServidorPesquisado');    // Servidor Editado na pesquisa do sistema do GRH
# Define o horário
date_default_timezone_set("America/Sao_Paulo");
setlocale(LC_ALL, 'pt_BR');
setlocale(LC_CTYPE, 'pt_BR');

/**
 * Função que é chamada automaticamente pelo sistema
 * para carregar na memória uma classe no exato momento
 * que a classe é instanciada.
 * 
 * @param  $classe = a classe instanciada
 */
function autoload($classe)
{
    # Array com as pastas existentes
    $pastasClasses     = [PASTA_CLASSES_GERAIS, PASTA_CLASSES, PASTA_CLASSES_ADMIN, PASTA_CLASSES_GRH];
    $categoriasClasses = ["class", "interface", "container", "html", "outros", "rel", "bd", "documento", "w3"];

    # Percorre as pastas
    foreach ($pastasClasses as $pasta) {
        # Percorre as categorias
        foreach ($categoriasClasses as $categoria) {
            if (file_exists($pasta . $categoria . ".{$classe}.php")) {
                include_once $pasta . $categoria . ".{$classe}.php";
            }
        }
    }
}

spl_autoload_register("autoload");

# Sobre o Sistema
$intra  = new Intra();
define("SISTEMA", $intra->get_variavel("sistemaContratos"));             # Nome do sistema
define("DESCRICAO", $intra->get_variavel("sistemaContratosDescricao"));  # Descrição do sistema
define("AUTOR", $intra->get_variavel("sistemaAutor"));             # Autor do sistema
define("EMAILAUTOR", $intra->get_variavel("sistemaAutorEmail"));   # Autor do sistema
# Versão do sistema
$versao = $intra->get_versaoAtual();
define("VERSAO", $versao[0]);                    # Versão do Sistema 								
define("ATUALIZACAO", date_to_php($versao[1]));  # Última Atualização