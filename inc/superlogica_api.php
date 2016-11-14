<?php

/**
 * User: Bruno
 * Date: 13/06/2016
 *
 * Helper para obtenção de informações através da API do SCODB.
 * Autores: Brendon Cambuí (85831)
 *          Bruno Pansani  (92348)
 *
 * Exemplo de uso:
 *
 * $apiHelper = new SLHelper();
 * $response = $apiHelper->authenticateShop("cid","senha");
 *
 * A variável $response terá como valor a resposta obtida pela API.
 *
 */

# Caso esteja desenvolvendo, comente a linha abaixo.
//error_reporting(0);

class SLAPIHelper
{
    /**
     * @var string
     * Valor padrão: URL onde está localizada a API.
     */
    const DEFAULT_URL = "https://api.superlogica.net/v2";

    /**
     * @var string
     * Valor padrão: Access Token para autenticação na API.
     */

    const DEFAULT_TOKEN = "";

    /**
     * @var string
     * Valor padrão: App Secret para autenticação na API.
     */
    const DEFAULT_APP_TOKEN = "";

    /**
     * @var string
     * Valor padrão: Mensagem de erro p/ arquivo não encontrado.
     */
    const ERROR_NOT_FOUND = "O servidor retornou 404. Arquivo não encontrado";
    
    /**
     * @var string
     * Valor padrão: Fim do 1º Lote e Início do Segundo.
     */
    const INICIO = "2016-06-25";
    
    /**
     * @var string
     * Valor padrão: Fim do primeiro Lote de Inscrições.
     */
    const LOTE_1 = "2016-11-09";
    
    /**
     * @valor string
     * Valor padrão: Fim do segundo lote de inscrições.
     */
    const LOTE_2 = "2016-11-16";

    /**
     * @var string
     * URL onde está localizada a API do SCODB. Este atributo está acessível através de
     * seus respectivos getters e setters e por padrão é definido no método construtor.
     * Valor padrão: {DEFAULT_URL}
     */
    private $url;

    /**
     * @var string
     * Variável que contém o App Token para conectar na API Superlógica. Este atributo está
     * acessível através de seus respectivos getters e setters e por padrão é definido
     * no método construtor. Valor padrão: {DEFAULT_TOKEN}
     */
    private $access_token;

    /**
     * @var string
     * Variável que contém o App Secret para conectar na API Superlógica. Este atributo está
     * acessível através de seus respectivos getters e setters e por padrão é definido
     * no método construtor. Valor padrão: {DEFAULT_SECRET}
     */
    private $app_token;

    /**
     * @var array
     * Array responsável por armazenar as informações que serão enviadas na requisição.
     * Valor padrão: array();
     */
    private $data;

    /**
     * SLHelper constructor.
     * Construtor do Helper. Responsável por facilitar a extração das informações obtidas
     * através da API. Parâmetros que podem ser passados no construtor: URL de Requisição,
     * Token, Secret, respectivamente.
     * @param string $url
     * @param string $token
     * @param string $app_token
     */
    public function __construct(
        $url = self::DEFAULT_URL,
        $access_token = self::DEFAULT_TOKEN,
        $app_token = self::DEFAULT_APP_TOKEN)
    {
        
        $this->url = $url;
        $this->access_token = $access_token;
        $this->app_token = $app_token;
    }


    /**
     * Método para requisição Cobrança:
     * Registra uma nova cobrança na API Superlógica utilizando a CID do Membro,
     * caso o membro ainda não seja um cliente na plataforma envia requisição
     * para o seu cadastro no sistema.
     * @param string $cid
     * @param array $cobranca
     * @return mixed
     */
    public function insereCobranca($cid, $forma, $id_cb = false)
    {

        $today = date("Y-m-d");
        if($id_cb) {
            $method = "PUT";
        } else {
            $method = "POST";
        }
        $produtos = array(
            "COMPO_RECEBIMENTO[1][ID_PRODUTO_PRD]" => "5",
            "COMPO_RECEBIMENTO[1][NM_QUANTIDADE_COMP]" => "1",
            "COMPO_RECEBIMENTO[1][VL_UNITARIO_PRD]" => "3.50",
            );
        if($today < date(self::LOTE_1)) {
            $produto = array(
            "COMPO_RECEBIMENTO[0][ID_PRODUTO_PRD]" => "3",
            "COMPO_RECEBIMENTO[0][NM_QUANTIDADE_COMP]" => "1",
            "COMPO_RECEBIMENTO[0][VL_UNITARIO_PRD]" => "50.00",
            "VL_EMITIDO_RECB" => "50.00");
             $produtos = array_merge($produtos,$produto);

            $venc = date('Y-m-d', strtotime("+1 days"));
        } else if ($today < date(self::LOTE_2)) {
            $produto = array(
            "COMPO_RECEBIMENTO[0][ID_PRODUTO_PRD]" => "4",
            "COMPO_RECEBIMENTO[0][NM_QUANTIDADE_COMP]" => "1",
            "COMPO_RECEBIMENTO[0][VL_UNITARIO_PRD]" => "75.00",
            "VL_EMITIDO_RECB" => "78.50");
            $produtos = array_merge($produtos,$produto);

            $venc = date('Y-m-d', strtotime("+3 days"));
        }
        
        if($forma != 0) {
            $venc = date('Y-m-d');
        }
        
        $v = explode("-",$venc);
        $vencimento = $v[1]."/".$v[2]."/".$v[0];



        $params = array("IDENTIFICADOR" => $cid,
                "DT_VENCIMENTO_RECB" => $vencimento,
                "ID_FORMAPAGAMENTO_RECB" => $forma,
				"FL_CIELOFORCARPAGAMENTO_RECB" => 1,
                "ID_CONTA_CB" => 3);
        
        if($id_cb) {
            $params["ID_RECEBIMENTO_RECB"] = $id_cb;
        }
        
        $params = array_merge($params,$produtos);
        
        # Algoritmo
        $url_params = http_build_query ($params);     

        
        $url = $this->url . "/financeiro/cobranca?" . $url_params;
        return json_decode($this->makeRequest($url,$method),true)[0];
    }
    
    
    public function clientFromCid($cid)
    {
        $method = "GET";
        # Verifica se os parâmetros foram recebidos
        if (!isset($cid)) return false;

        # Um ID é um número inteiro.
        //$cid = intval($cid);
        
        # Algoritmo
        //$url_params = http_build_query ($params);     
        
        $url = $this->url . "/financeiro/clientes?identificador=".$cid;
        $return = json_decode($this->makeRequest($url,$method),true);
        
        return $return[0];
    }
    
    public function createClient($member)
    {
        $method = "POST";
        $params = array(
            "ST_NOME_SAC" => $member->getNome(),
            "ST_NOMEREF_SAC" => $member->getNome(),
            "ST_DIAVENCIMENTO_SAC" => 0,
            "ST_EMAIL_SAC" => $member->getEmail(),
            "SENHA" => $member->getCid().$member->getCid(),
            "SENHA_CONFIRMACAO" => $member->getCid().$member->getCid(),
            "ST_TELEFONE_SAC" => $member->getTelefone(),
            "DESABILITAR_MENSALIDADE" => 1,
            //"NM_CARTAO_SAC" => $member->get(),
            //"NM_MESCARTAOVENCIMENTO_SAC" => $member->get(),
            //"NM_ANOCARTAOVENCIMENTO_SAC" => $member->get(),
            //"ST_CODIGOCONTABIL_SAC" => $member->get(),
            //"FL_RETERISSQN_SAC" => $member->get(),
            "TX_OBSERVACAO_SAC" => "Cliente gerado pelo Sistema de Inscrições do 37º CEOD SP em ".date("d/m/Y"),
            "FL_SINCRONIZARFORNECEDOR_SAC" => 0,
            "identificador" => $member->getCid()
            );
        
        # Algoritmo
        $url_params = http_build_query ($params);     
        
        $url = $this->url . "/financeiro/clientes?" . $url_params;
        $return = json_decode($this->makeRequest($url,$method),true);
        
        return $return[0];
    }
    
    public function clientFromId($id)
    {
        $method = "GET";
        # Verifica se os parâmetros foram recebidos
        if (!isset($id)) return false;


        $url = $this->url . "/financeiro/clientes/".$id;
        $return = json_decode($this->makeRequest($url,$method),true);
        
        return $return[0];
    }
    
    public function cobrancaFromId($id)
    {
        $method = "GET";
        # Verifica se os parâmetros foram recebidos
        if (!isset($id)) return false;

        # Um ID é um número inteiro.
        $id = intval($id);

        $url = $this->url . "/financeiro/cobranca/".$id;
        $return = json_decode($this->makeRequest($url,$method),true);
        
        return $return[0];
    }
	
	 public function cobrancasByStatus($status)
    {
        $method = "GET";
        
        $params = array(
            "apenasColunasPrincipais" => 1,
            "status" => $status,
            "dtInicio" => "01/01/2016",
            "dtFim" => "12/31/2016"
            );
        
        # Algoritmo
        $url_params = http_build_query ($params);     
        
        $url = $this->url . "/financeiro/cobranca?" . $url_params;
        $return = json_decode($this->makeRequest($url,$method),true);
        
        return $return;
    }
    
    public function allClients()
    {
        $method = "GET";
        
        $params = array("status" => 0);
                
        $url_params = http_build_query ($params);    

        $url = $this->url . "/financeiro/clientes?" . $params;
        return json_decode($this->makeRequest($url,$method));
    }
    
    public function urlCartao($email,$bandeira,$callback)
    {
        $method = "GET";
        $params = array(
            "email" => $email,
            "callback" => $callback,
            "bandeira" => $bandeira
            );
        
        # Algoritmo
        $url_params = http_build_query ($params);     
        
        $url = $this->url . "/financeiro/clientes/urlcartao?".$url_params;
        //print_r($url);
        $return = json_decode($this->makeRequest($url,$method),true);

        return $return["url"];
    }
    
    public function clienteToken($email)
    {
        $method = "GET";
        $params = array(
            "email" => $email
            );
        
        # Algoritmo
        $url_params = http_build_query ($params);     
        
        $url = $this->url . "/financeiro/clientes/token?".$url_params;
        //print_r($url);
        $return = json_decode($this->makeRequest($url,$method),true);
        
        return $return["token"];
    }

    /**
     * @param string $url
     * @return string
     */
    private function makeRequest($url,$method)
    {
        # Verifica se os parâmetros foram recebidos
        if (!isset($url)) return false;

        # Algoritmo
        
        $opts = array(
          'http'=>array(
            'method'=>$method,
            'header'=>"Access_token: " . $this->access_token . "\r\n" .
                      "App_token: " . $this->app_token . "\r\n"
          ),
          'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ),
        );
        
        $context = stream_context_create($opts);
        
        $data = file_get_contents($url, false, $context);
        
        
        
        $data_print = print_r($data, TRUE);
        echo "<div id=\"demo\" class=\"collapse\"><pre>".$data_print."</pre></div>";

        # Verifica se a requisição foi validada pelo servidor
        if ($data === false) {
            die(self::ERROR_NOT_FOUND);
        } else {
            return $data;
        }
    }

    #
    # Seguem abaixo, os getters e setters desta classe.
    #
	public function getUrl(){
		return $this->url;
	}
	public function setUrl($url){
		$this->url = $url;
	}
	public function getAccess_token(){
		return $this->access_token;
	}

	public function setAccess_token($access_token){
		$this->access_token = $access_token;
	}

	public function getApp_token(){
		return $this->app_token;
	}

	public function setApp_token($app_token){
		$this->app_token = $app_token;
	}

	public function getData(){
		return $this->data;
	}

	public function setData($data){
		$this->data = $data;
	}

}