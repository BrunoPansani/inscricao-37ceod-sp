<?php 

require 'membro.class.php';

class Cobranca 
{
	const BOLETO = 0;
	const CREDITO = 3;
	const DEBITO = 4;
    public static $formas_pgto = array(0,3,4);
    public static $produtos = array(
    	array(
    		"id" => 3,
    		"qtd" => 1,
    		"valor" => 50.00
    	),
    	array(
    		"id" => 4,
    		"qtd" => 1,
    		"valor" => 2.50
		));
    
    private $cid_cliente;
    private $id_cliente;
    private $produtos;
    private $valor_total;
    private $data_venc;
    private $forma_pgto;
    
    public function __construct(Membro $member, ) {
    	
    	if($source == "db") {
	        $this->id = $member["id"];
	        $this->cid = $member["cid"];
	        $this->nome = $member["nome"];
	        $this->dataNascimento = $member["dataNascimento"];
	        $this->email = $member["email"];
	        $this->regular = $member["regular"];
	        
    	}
    }
    
    public function getCid_cliente(){
		return $this->cid_cliente;
	}

	public function setCid_cliente($cid_cliente){
		$this->cid_cliente = $cid_cliente;
	}

	public function getId_cliente(){
		return $this->id_cliente;
	}

	public function setId_cliente($id_cliente){
		$this->id_cliente = $id_cliente;
	}

	public function getProdutos(){
		return $this->produtos;
	}

	public function setProdutos($produtos){
		$this->produtos = $produtos;
	}

	public function getValor_total(){
		return $this->valor_total;
	}

	public function setValor_total($valor_total){
		$this->valor_total = $valor_total;
	}

	public function getData_venc(){
		return $this->data_venc;
	}

	public function setData_venc($data_venc){
		$this->data_venc = $data_venc;
	}

	public function getForma_pgto(){
		return $this->forma_pgto;
	}

	public function setForma_pgto($forma_pgto){
		$this->forma_pgto = $forma_pgto;
	}
    
	public function create () {
		
		$database = new medoo([
		    'database_type' => 'mysql',
		    'database_name' => 'c9',
		    'server' => getenv('IP'),
		    'username' => substr(getenv('C9_USER'), 0, 16),
		    'password' => '',
		    'charset' => 'utf8'
		]);

	    if(!$this->getTamanhoCamiseta()) {
	        throw new Exception("Você deve selecionar um tamanho de camiseta");
	    }
	    
	    if(!$this->getGrauDesejado()) {
            throw new Exception("Você deve selecionar ao menos a opção \"Nenhum\" no campo Grau Desejado.");
	    }
	    
	    if(!$this->getDataInscricao()) {
        	$this->setDataInscricao(); 
	    }
	    
	    $this->dataUltimoAcesso = date("Y-m-d H:i:s");
        
        $this->setIpUltimoAcesso();
	    
	    $create = $database->insert('inscritos', [
	    	'cid' => $this->cid,
			'nome' => $this->nome,
			'dataNascimento' => $this->dataNascimento,
			'email' => $this->email,
			'telefone' => $this->telefone,
			'regular' => $this->regular,
			'macom' => $this->macom,
			'grau' => $this->grau,
			'capitulo' => $this->capitulo,
			'capituloNro' => $this->capituloNro,
			'cavaleiro' => $this->cavaleiro,
			'convento' => $this->convento,
			'conventoNro' => $this->conventoNro,
			'tamanhoCamiseta' => $this->tamanhoCamiseta,
			'grauDesejado' => $this->grauDesejado,
			'dataInscricao' => $this->dataInscricao,
			'dataUltimoAcesso' => $this->dataUltimoAcesso,
			'ipUltimoAcesso' => $this->ipUltimoAcesso
    	]);
		
	    if(intval($create->id)) {
	    	return true;
	    } else {
	       return false;
	    }
	}
	
	
    
}

?>