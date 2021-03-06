<?php

class Empresa
{

    /**
     * Abriga as várias rotina referentes a concurso
     *
     * @author André Águia (Alat) - alataguia@gmail.com
     * 
     * @var private $idConcurso integer null O id do concurso
     */
    private $idEmpresa = null;

##############################################################

    public function __construct($idEmpresa = null)
    {
        /**
         * Inicia a Classe somente
         * 
         * @param $idContrato integer null O id do concurso
         * 
         * @syntax $concurso = new Concurso([$idConcurso]);
         */
        $this->idEmpresa = $idEmpresa;
    }

##############################################################

    public function getDados($idEmpresa = null)
    {

        /**
         * Informa os dados da base de dados
         * 
         * @param $idConcurso integer null O id do concurso
         * 
         * @syntax $concurso->get_dados([$idConcurso]);
         */
        # Joga o valor informado para a variável da classe
        if (!vazio($idEmpresa)) {
            $this->idEmpresa = $idEmpresa;
        }

        # Conecta ao Banco de Dados
        $contratos = new Contratos();

        # Verifica se foi informado
        if (vazio($this->idEmpresa)) {
            alert("É necessário informar o id da Empresa.");
            return;
        }

        # Pega os dados
        $select = 'SELECT * 
                     FROM tbempresa
                    WHERE idEmpresa = ' . $this->idEmpresa;

        $row = $contratos->select($select, false);

        # Retorno
        return $row;
    }

    #####################################################################################

    public function getRazaoSocial($idEmpresa)
    {

        # Joga o valor informado para a variável da classe
        if (!vazio($idEmpresa)) {
            $this->idEmpresa = $idEmpresa;
        }

        # Conecta ao Banco de Dados
        $contratos = new Contratos();

        # Verifica se foi informado
        if (vazio($this->idEmpresa)) {
            alert("É necessário informar o id da Empresa.");
            return;
        }

        # Pega os dados
        $select = 'SELECT razaoSocial 
                     FROM tbempresa
                    WHERE idEmpresa = ' . $this->idEmpresa;

        $row = $contratos->select($select, false);

        # Retorno
        return $row[0];
    }

    ##########################################################################################

    public function getTelefones($idEmpresa)
    {

        # Função que retorna os telefones do servidor cadastrado no sistema
        #
        # Parâmetro: id do servidor

        $dados = $this->getDados($idEmpresa);
        $retorno = null;

        if (!empty($dados["telefone1"])) {
            $retorno .= "{$dados["telefone1"]}<br/>";
        }

        if (!empty($dados["telefone2"])) {
            $retorno .= "{$dados["telefone2"]}<br/>";
        }

        if (!empty($dados["telefone3"])) {
            $retorno .= "{$dados["telefone3"]}";
        }

        return $retorno;
    }

    ##########################################################################################

    public function getEmails($idEmpresa)
    {

        # Função que retorna os telefones do servidor cadastrado no sistema
        #
        # Parâmetro: id do servidor

        $dados = $this->getDados($idEmpresa);
        $retorno = null;

        if (!empty($dados["email1"])) {
            $retorno .= "{$dados["email1"]}<br/>";
        }

        if (!empty($dados["email2"])) {
            $retorno .= "{$dados["email2"]}<br/>";
        }

        if (!empty($dados["email3"])) {
            $retorno .= "{$dados["email3"]}";
        }

        return $retorno;
    }

    ##########################################################################################

    public function getContatos($idEmpresa)
    {

        # Função que retorna os telefones do servidor cadastrado no sistema
        #
        # Parâmetro: id do servidor

        $dados = $this->getDados($idEmpresa);
        $retorno = null;

        if (!empty($dados["contato"])) {
            $retorno .= "{$dados["contato"]}<br/>";
        }

        if (!empty($dados["usuarioSei"])) {
            $retorno .= "SEI: {$dados["usuarioSei"]}<br/>";
        }

        return $retorno;
    }

    ##########################################################################################

    public function getContatoComTel($idEmpresa)
    {

        # Função que retorna o contato
        #
        # Parâmetro: id do servidor

        $dados = $this->getDados($idEmpresa);
        $retorno = null;

        if (!empty($dados["contato"])) {
            return $dados["contato"]."<br/>".$this->getTelefones($idEmpresa);
        }
        
        
    }

    ##########################################################################################


    public function getEmpresaCnpj($idEmpresa)
    {

        $dados = $this->getDados($idEmpresa);

        $retorno = $dados["razaoSocial"];

        if (!empty($dados["cnpj"])) {
            $retorno .= "<br/><p id='f10'> CNPJ: {$dados["cnpj"]}</p>";
        }

        return $retorno;
    }

    ##########################################################################################

    public function getCnpj($idEmpresa)
    {

        $dados = $this->getDados($idEmpresa);
        $retorno = trataNulo($dados["cnpj"]);
        return $retorno;
    }

    ##########################################################################################

    public function getEmpresa($idEmpresa)
    {

        $dados = $this->getDados($idEmpresa);
        return $dados["razaoSocial"];
    }

    ##########################################################################################

    public function exibeEmpresaRelatorio($idEmpresa)
    {

        $dados = $this->getDados($idEmpresa);
        p($dados["razaoSocial"], "pComissaoImpressao");
    }

    ##########################################################################################

    public function getEndereco($idEmpresa)
    {

        $dados = $this->getDados($idEmpresa);

        if (!empty($dados["endereco"])) {
            return "{$dados["endereco"]}<br/>{$dados["bairro"]}<br/>{$dados["cep"]}";
        } else {
            return null;
        }
    }

    ###########################################################

    function exibeDados($idEmpresa, $idUsuario)
    {

        $conteudo = $this->getDados($idEmpresa);

        # Monta o array de exibição
        $dados = [
            ["Razão Social", $conteudo["razaoSocial"]],
            ["CNPJ", $conteudo["cnpj"]],
            ["Telefone", $this->getTelefones($idEmpresa)],
            ["Email", $this->getEmails($idEmpresa)],
            ["Contatos", $this->getContatos($idEmpresa)],
            ["Endereço", $this->getEndereco($idEmpresa)],
        ];

        # Monta a tabela
        $tabela = new Tabela();
        $tabela->set_titulo("Empresa Contratada");
        $tabela->set_label(array("Servidor", "Tipo"));
        $tabela->set_align(array("left", "left"));
        $tabela->set_width(array(30, 70));
        #$tabela->set_numeroOrdem(true);
        $tabela->set_conteudo($dados);
        $tabela->show();

        # Editar
        if (Verifica::acesso($idUsuario, 9)) {
            $div = new Div("divEdita1Comissao");
            $div->abre();

            $div = new Div("divEdita2");
            $div->abre();

            $botaoEditar = new Link("Editar", "cadastroEmpresa.php?fase=editar&id={$idEmpresa}");
            $botaoEditar->set_class('tiny button secondary');
            $botaoEditar->set_title('Editar situação');
            $botaoEditar->show();

            $div->fecha();
            $div->fecha();
        }
    }

    ##############################################################

    public function getNumContratos($idEmpresa = null)
    {
        # Joga o valor informado para a variável da classe
        if (!vazio($idEmpresa)) {
            $this->idEmpresa = $idEmpresa;
        }

        # Conecta ao Banco de Dados
        $contratos = new Contratos();

        # Verifica se foi informado
        if (vazio($this->idEmpresa)) {
            alert("É necessário informar o id da Empresa.");
            return;
        }

        # Pega os dados
        $select = 'SELECT idEmpresa
                 FROM tbcontrato
                WHERE idEmpresa = ' . $this->idEmpresa;

        $numero = $contratos->count($select, false);

        # Retorno
        return $numero;
    }

}
