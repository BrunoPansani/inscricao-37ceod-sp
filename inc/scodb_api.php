<?php

/**
 * Created by PhpStorm.
 * User: Brendon
 * Date: 13/04/2016
 * Time: 17:37
 *
 * Helper para obtenção de informações através da API do SCODB.
 * Autores: Brendon Cambuí (85831)
 *          Bruno Pansani  (92348)
 *
 * Exemplo de uso:
 *
 * $apiHelper = new SCODBAPIHelper();
 * $response = $apiHelper->authenticateShop("cid","senha");
 *
 * A variável $response terá como valor a resposta obtida pela API.
 *
 */

# Caso esteja desenvolvendo, comente a linha abaixo.
//error_reporting(0);

class SCODBAPIHelper
{
    /**
     * @var string
     * Valor padrão: URL onde está localizada a API.
     */
    const DEFAULT_URL = "";

    /** 
     * @var string
     * Valor padrão: Usuário para autenticação na API.
     */
    const DEFAULT_USERNAME = "";

    /**
     * @var string
     * Valor padrão: Senha para autenticação na API.
     */
    const DEFAULT_PASSWORD = "";

    /**
     * @var string
     * Valor padrão: Mensagem de erro p/ arquivo não encontrado.
     */
    const ERROR_NOT_FOUND = "O servidor retornou 404. Arquivo não encontrado";

    /**
     * @var string
     * URL onde está localizada a API do SCODB. Este atributo está acessível através de
     * seus respectivos getters e setters e por padrão é definido no método construtor.
     * Valor padrão: {DEFAULT_URL}
     */
    private $url;

    /**
     * @var string
     * Variável que contém o usuário para conectar na API do SCODB. Este atributo está
     * acessível através de seus respectivos getters e setters e por padrão é definido
     * no método construtor. Valor padrão: {DEFAULT_USERNAME}
     */
    private $username;

    /**
     * @var string
     * Variável que contém senha para conectar na API do SCODB. Este atributo está
     * acessível através de seus respectivos getters e setters e por padrão é definido
     * no método construtor. Valor padrão: {DEFAULT_PASSWORD}
     */
    private $password;

    /**
     * @var array
     * Array responsável por armazenar as informações que serão enviadas na requisição.
     * Valor padrão: array();
     */
    private $data;

    /**
     * SCODBAPIHelper constructor.
     * Construtor do Helper. Responsável por facilitar a extração das informações obtidas
     * através da API. Parâmetros que podem ser passados no construtor: URL de Requisição,
     * Usuário, Senha, respectivamente.
     * @param string $url
     * @param string $username
     * @param string $password
     */
    public function __construct(
        $url = self::DEFAULT_URL,
        $username = self::DEFAULT_USERNAME,
        $password = self::DEFAULT_PASSWORD)
    {
        $this->url = $url;
        $this->username = $username;
        $this->password = $password;
    }

    #
    # Seguem abaixo, os métodos disponíveis para autenticação na API do SCODB. Todos os
    # metodos retornam um array ou uma string (em caso de acesso negado), provido pelo
    # XML de obtido através da requisição. Os métodos são: authenticateShop,
    # memberForEvents, chapters.
    #

    /**
     * Método para requisição authenticateShop:
     * Prove o login do membro através de seu usuário e senha previamente registrados no
     * SISDM. Parâmetros: cid e senha.
     * @param string $cid
     * @param string $password
     * @return mixed
     */
    public function authenticateShop($cid, $password)
    {
        # Verifica se os parâmetros foram recebidos
        if (!isset($cid) || !isset($password)) return false;

        # A API atualmente só suporta CID como número inteiro.
        $cid = intval($cid);

        # Algoritmo
        $password = md5($password);
        $url = $this->url . "/authenticateshop/$cid/$password";
        return $this->xml2array($this->makeRequest($url));
    }

    /**
     * Método para requisição memberForEvents:
     * Prove informações básicas do DeMolay requerido. Parâmetro: cid.
     * @param string $cid
     * @return mixed
     */
    public function memberForEvents($cid)
    {
        # Verifica se os parâmetros foram recebidos
        if (!isset($cid)) return false;

        # A API atualmente só suporta CID como número inteiro.
        $cid = intval($cid);

        # Algoritmo
        $url = $this->url . "/memberforevents/$cid";
        $return = $this->xml2array($this->makeRequest($url));

        if($return["member"]) {
            $member = new Membro($return["member"]);
            return $member;
        } else {
            return false;
        }

    }

    /**
     * Método para requisição chapters:
     * Prove informações básicas do Capítulo requerido. Parâmetro: número do capítulo.
     * @param int $number
     * @return mixed
     */
    public function chapters($number)
    {
        # Verifica se os parâmetros foram recebidos
        if (!isset($number)) return false;

        # O número do capítulo deve ser inteiro.
        $number = intval($number);

        # Algoritmo
        $url = $this->url . "/chapters/$number";
        return $this->xml2array($this->makeRequest($url));
    }

    #
    # Seguem abaixo, os métodos privados, apenas de uso interno desta classe.
    # Utilizados para extrair e transformar as informações obtidas.
    #

    /**
     * @param string $url
     * @return string
     */
    private function makeRequest($url)
    {
        # Verifica se os parâmetros foram recebidos
        if (!isset($url)) return false;

        # Algoritmo
        $auth = base64_encode($this->username . ":" . $this->password);
        $context = stream_context_create(
            array(
                'http' => array(
                    'header' => "Authorization: Basic $auth"
                ),
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ),
            )
        );
        $data = file_get_contents($url, false, $context);

        # Verifica se a requisição foi validada pelo servidor
        if ($data === false) {
            die(self::ERROR_NOT_FOUND);
        } else {
            return $data;
        }
    }

    /**
     * @param string $string
     * @return array
     */
    private function xml2array($string)
    {
        $xml = simplexml_load_string($string);
        $name = $xml->getName();
        $json = json_encode($xml);
        $array = json_decode($json, true);
        $return[$name] = $array;
        return $return;
    }

    #
    # Seguem abaixo, os getters e setters desta classe.
    #

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

}