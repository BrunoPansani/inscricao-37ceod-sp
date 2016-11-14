<?php 

class Membro 
{
    public static $TAMANHOS = array("P","M","G","GG");
    
    public static $GRAUS = array("Nenhum","Ébano","Anôn","Cadência");
    
    private $id;
    private $cid;
    private $nome;
    private $dataNascimento;
    private $email;
    private $telefone;
    private $regular;
    private $macom;
    private $grau;
    private $capitulo;
    private $capituloNro;
    private $cavaleiro;
    private $convento;
    private $conventoNro;
    private $tamanhoCamiseta;
    private $grauDesejado;
    private $dataInscricao;
    private $dataUltimoAcesso;
    private $ipUltimoAcesso;
    private $confirmada;
    private $cb_forma_pgto;
    private $cb_data_venc;
	private $cb_link_2via;
	private $cb_id;
	private $cb_valor;
	private $lote;
	private $cb_liquidada;
	private $token;
    
    public function __construct($member = false, $source = "api") {
    	
    	if($source == "db") {
	        $this->id = $member["id"];
	        $this->cid = $member["cid"];
	        $this->nome = $member["nome"];
	        $this->dataNascimento = $member["dataNascimento"];
	        $this->email = $member["email"];
	        $this->regular = $member["regular"];
	        $this->macom = $member["macom"];
	        $this->confirmada = $member["confirmada"];
	        $this->formaPgto = $member["formaPgto"];
	        $this->grau = $member["grau"];
	        $this->telefone = $member["telefone"];
	        $this->capitulo = $member["capitulo"];
	        $this->capituloNro = $member["capituloNro"];
	        $this->cavaleiro = $member["cavaleiro"];
	        $this->convento = titleCase($member["convento"]);
	        $this->conventoNro = $member["conventoNro"];
	        $this->tamanhoCamiseta = $member["tamanhoCamiseta"];
			$this->grauDesejado = $member["grauDesejado"];
	        $this->dataInscricao = $member["dataInscricao"];
    		$this->dataUltimoAcesso = $member["dataUltimoAcesso"];
    		$this->ipUltimoAcesso = $member["ipUltimoAcesso"];
    		$this->lote = $member["lote"];
    		$this->cb_data_venc = $member["cb_data_venc"];
    		$this->cb_link_2via = $member["cb_link_2via"];
    		$this->cb_forma_pgto = $member["cb_forma_pgto"];
    		$this->cb_id = $member["cb_id"];
    		$this->cb_valor = $member["cb_valor"];
    		$this->cb_liquidada = $member["cb_liquidada"];
    		$this->token = $member["token"];
    		
    	} else if ($source == "api") {
	        $this->id = $member["id"];
	        $this->cid = $member["cid"];
	        $this->nome = titleCase($member["name"]);
	        $this->dataNascimento = implode("-",array_reverse(explode("/",$member["birthday"])));
	        $this->email = strtolower($member["email"]);
	        $this->regular = $member["regularity"];
	        $this->macom = $member["macom"];
	        $this->grau = $member["degree"];
	        if(!is_array($telefone)) 
		    	$this->telefone = "(".intval($member["phone"]["DDD_celular"]).") ".intval($member["phone"]["celular"]);
		    else
		    	$this->telefone = "";
	        $this->capitulo = titleCase($member["chapter"]);
	        $this->capituloNro = $member["chapterNumber"];
	        $this->cavaleiro = $member["knight"];
	        $this->convento = titleCase($member["convent"]);
	        $this->conventoNro = $member["conventNumber"];
	        $this->confirmada = $member["confirmada"];
	        $this->lote = $member["lote"];
    	}
    }
    
    public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getCid(){
		return $this->cid;
	}

	public function getNome(){
		return $this->nome;
	}
	public function setnome($nome){
		$this->nome = $nome;
	}

	public function getDataNascimento(){
		return implode("/",array_reverse(explode("-",$this->dataNascimento)));
	}

	public function setDataNascimento($dataNascimento, $raw = false){
		if(!$raw) {
			$date = implode("-",array_reverse(explode("/",$dataNascimento)));
			
			if($date < new DateTime()) {
			    throw new Exception("Data Inválida");
			}
		} else {
			$date = $dataNascimento;
		}
		$this->dataNascimento = $date;
	}

	public function getEmail(){
		return $this->email;
	}

	public function setEmail($email){
		$this->email = $email;
	}

	public function getRegular(){
		return $this->regular;
	}
	
	public function isRegular(){
		if($this->regular == "Sim") 
		    return true;
	    else
		    return false;
	}

	public function getMacom(){
		return $this->macom;
	}

	public function getGrau(){
		return $this->grau;
	}
	public function getConfirmada(){
		return $this->confirmada;
	}

	public function setConfirmada($confirmada){
		$this->confirmada = $confirmada;
	}

	public function getFormaPgto(){
		return $this->formaPgto;
	}
	
	public function getFormaPgtoTexto(){
		if($this->formaPgto == 0) {
			return "Boleto Bancário";
		} else {
			return "Cartão de Crédito";
		}
	}

	public function setFormaPgto($formaPgto){
		$this->formaPgto = $formaPgto;
	}
	public function getCapitulo(){
		if(!$this->capituloNro) return false;
		return $this->capitulo.", nº ".$this->capituloNro;
	}

	public function getCavaleiro(){
		return $this->cavaleiro;
	}

	public function getConvento(){
		if(!$this->conventoNro) return false;
		return $this->convento.", nº ".$this->conventoNro;
	}

    public function getTelefone(){
		return $this->telefone;
	}

	public function setTelefone($telefone){
		if(!is_array($telefone)) 
	    	$this->telefone = $telefone;
	    else
	    	$this->telefone = "";
	}

	public function getTamanhoCamiseta(){
		return $this->tamanhoCamiseta;
	}

	public function setTamanhoCamiseta($tamanhoCamiseta)
	{   $tamanhoCamiseta = (string)$tamanhoCamiseta;
	    if(in_array($tamanhoCamiseta, self::$TAMANHOS)) {
		    $this->tamanhoCamiseta = $tamanhoCamiseta;
		} else {
            throw new Exception("Tamanho de Camiseta Inválido");
	    }
	}

	public function getGrauDesejado(){
		return $this->grauDesejado;
	}

	public function setGrauDesejado($grauDesejado){
		if(in_array($grauDesejado, self::$GRAUS)) {
		    $this->grauDesejado = $grauDesejado;
	    } else {
	        throw new Exception ("Grau Inválido");
	    }
	}

	public function getDataInscricao(){
		return $this->dataInscricao;
	}

	public function setDataInscricao(){
		$this->dataInscricao = date("Y-m-d H:i:s");
	}

	public function getIpUltimoAcesso(){
		return $this->ipUltimoAcesso;
	}

	public function setIpUltimoAcesso(){
		$this->ipUltimoAcesso = $_SERVER["REMOTE_ADDR"];
	}
	
	public function getCb_data_venc(){
		return implode("/",array_reverse(explode("-",$this->cb_data_venc)));
	}

	public function setCb_data_venc($cb_data_venc){
		$dt = explode("/",$cb_data_venc);
		$this->cb_data_venc = $dt[2] . '-' . $dt[0] . '-' . $dt[1];
	}

	public function getCb_link_2via(){
		return $this->cb_link_2via;
	}

	public function setCb_link_2via($cb_link_2via){
		$this->cb_link_2via = $cb_link_2via;
	}

	public function getCb_forma_pgto(){
		return $this->cb_forma_pgto;
	}

	public function setCb_forma_pgto($cb_forma_pgto){
		$this->cb_forma_pgto = $cb_forma_pgto;
	}

	public function getCb_id(){
		return $this->cb_id;
	}

	public function setCb_id($cb_id){
		$this->cb_id = $cb_id;
	}

	public function getCb_valor(){
		return $this->cb_valor;
	}
	
	public function getCb_valorTexto(){
		return "R$ ".$this->cb_valor;
	}

	public function setCb_valor($cb_valor){
		$this->cb_valor = $cb_valor;
	}

	public function getLote(){
		return $this->lote;
	}

	public function setLote($lote){
		$this->lote = $lote;
	}
	
	public function getCb_liquidada(){
		return implode("/",array_reverse(explode("-",$this->cb_liquidada)));
	}

	public function setCb_liquidada($cb_liquidada){
		$parts = explode("/",$cb_liquidada);
		$this->cb_liquidada = $parts[2] . '-' . $parts[0] . '-' . $parts[1];
		if($this->cb_liquidada == "0000-00-00") {
			$this->cb_liquidada = NULL;
		}
	}
	
	public function getToken(){
		return $this->token;
	}

	public function setToken($token){
		$this->token = $token;
	}
	
	public function create ($database) {

	    if(!$this->getTamanhoCamiseta()) {
	        throw new Exception("Você deve selecionar um tamanho de camiseta");
	    }
	    
	    if(!$this->getGrauDesejado()) {
            $this->setGrauDesejado("Nenhum");
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
			'ipUltimoAcesso' => $this->ipUltimoAcesso,
			'confirmada' => $this->confirmada,
	        'formaPgto' => $this->formaPgto,
	        'lote' => $this->lote
    	]);
    	
	    if($create) {
	    	return $create;
	    } else {
	       return false;
	    }
	}

	public function updateCobranca ($cb,$database) {
		
		$this->setCb_id($cb["id_recebimento_recb"]);
		$this->setCb_link_2via($cb["link_2via"]);
		$this->setCb_valor($cb["vl_total_recb"]);
		$this->setCb_data_venc($cb["dt_vencimento_recb"]);
		$this->setCb_liquidada($cb["dt_liquidacao_recb"]);
		$this->setCb_forma_pgto($cb["id_formapagamento_recb"]);
		
	    $this->dataUltimoAcesso = date("Y-m-d H:i:s");
        
        $this->setIpUltimoAcesso($_SERVER["REMOTE_ADDR"]);
	    
	    $cobranca = $database->update('inscritos', [
	    	'cb_data_venc' => $this->cb_data_venc,
			'cb_link_2via' => $this->cb_link_2via,
			'cb_forma_pgto' => $this->cb_forma_pgto,
			'cb_id' => $this->cb_id,
			'cb_valor' => $this->cb_valor,
			'cb_liquidada' => $this->cb_liquidada,
			'dataUltimoAcesso' => $this->dataUltimoAcesso,
			'ipUltimoAcesso' => $this->ipUltimoAcesso
    	], [
			"id" => $this->id
		]);
		
	    if($cobranca>0) {
	    	return $cobranca;
	    } else {
	       return false;
	    }
	}
	
	public function saveToken ($database) {
		
		$this->dataUltimoAcesso = date("Y-m-d H:i:s");
        
        $this->setIpUltimoAcesso($_SERVER["REMOTE_ADDR"]);
	    
	    $register = $database->update('inscritos', [
			'token' => $this->token,
			'dataUltimoAcesso' => $this->dataUltimoAcesso,
			'ipUltimoAcesso' => $this->ipUltimoAcesso
    	], [
			"id" => $this->id
		]);
		
	    if($register>0) {
	    	return $register;
	    } else {
	       return false;
	    }
	}
	
	public function deleteToken ($database) {
		
	    $register = $database->update('inscritos', [
			'token' => ''
    	], [
			"id" => $this->id
		]);
		
	    if($register>0) {
	    	return $register;
	    } else {
	       return false;
	    }
	}
}

?>