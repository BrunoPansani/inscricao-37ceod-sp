<h4>Método de Pagamento:</h4>
<div class="select">
    <div class="col-sm-12">
        <select id="metodoPagamento" name="pagamento" required="">
            <option value="" selected>Selecione um meio de pagamento</option>
            <option value="0">Boleto Bancário</option>
            <option value="3">Cartão de Crédito</option>
        </select>
    </div>
    <div class="col-sm-12" id="divBandeiraCartao">
        <select id="bandeiraCartao" name="bandeira">
            <option value="" selected>Selecione uma bandeira</option>
            <option value="visa">Visa</option>
            <option value="mastercard">MasterCard</option>
            <option value="diners">Diners</option>
            <option value="amex">American Express</option>
            <option value="elo">Elo</option>
        </select>
    </div>
</div>

<script>

$('#divBandeiraCartao').hide(); 
$('#metodoPagamento').change(function(){
    if($('#metodoPagamento').val() == '3') {
        $('#divBandeiraCartao').show();
        $('#bandeiraCartao').prop("required", true);
    } else {
        $('#divBandeiraCartao').hide(); 
        $('#bandeiraCartao').prop("required", false);
    } 
});

</script>