<?php
define("EMAIL_PAGSEGURO","MEU-EMAIL-PAGSEGURO");
define("TOKEN_PAGSEGURO","MEU-TOKEN-PAGSEGURO");
define("TOKEN_SANDBOX","");



function iniciaPagamentoAction(){

	$data['token'] = TOKEN_PAGSEGURO; //token teste SANDBOX

	$data = http_build_query($data);
	$url = 'https://ws.pagseguro.uol.com.br/v2/sessions';

	$curl = curl_init();

	$headers = array('Content-Type: application/x-www-form-urlencoded; charset=ISO-8859-1'
		);

	curl_setopt($curl, CURLOPT_URL, $url . "?email=" . EMAIL_PAGSEGURO);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers );
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true );
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	//curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	curl_setopt($curl, CURLOPT_HEADER, false);
	$xml = curl_exec($curl);

	curl_close($curl);

	$xml= simplexml_load_string($xml);
	$idSessao = $xml->id;

	return $idSessao;
	exit;

}

function efetuaPagamentoCartao($dados, $i) {

	$data['token'] = TOKEN_PAGSEGURO; //token sandbox ou produção
	$data['receiverEmail'] =  $dados['receiverEmail'];
	$data['paymentMode'] = 'default';
	$data['senderHash'] = $dados['hash']; //gerado via javascript
	$data['creditCardToken'] = $dados['creditCardToken']; //gerado via javascript
	$data['paymentMethod'] = 'creditCard';	
	$data['senderName'] = $dados['senderName']; //nome do usuário deve conter nome e sobrenome
	$data['senderAreaCode'] = $dados['senderAreaCode'];
	$data['senderPhone'] = $dados['senderPhone'];
	$data['senderEmail'] = $dados['senderEmail'];

	if($dados['senderCPF'] != null){$data['senderCPF'] = $dados['senderCPF'];}
	if(isset($dados['senderCNPJ'])){$data['senderCNPJ'] = $dados['senderCNPJ'];}
	
	$data['installmentQuantity'] = $dados['installmentQuantity'];
	//$data['noInterestInstallmentQuantity'] = '1';
	$data['installmentValue'] = $dados['installmentValue']; //valor da parcela
	$data['creditCardHolderName'] = $dados['creditCardHolderName']; //nome do titular
	$data['creditCardHolderCPF'] = $dados['creditCardHolderCPF'];
	$data['creditCardHolderBirthDate'] = $dados['creditCardHolderBirthDate'];
	$data['creditCardHolderAreaCode'] = $dados['creditCardHolderAreaCode'];
	$data['creditCardHolderPhone'] = $dados['creditCardHolderPhone'];
	$data['billingAddressStreet'] = $dados['billingAddressStreet'];
	$data['billingAddressNumber'] = $dados['billingAddressNumber'];
	$data['billingAddressDistrict'] = $dados['billingAddressDistrict'];
	$data['billingAddressPostalCode'] = $dados['billingAddressPostalCode'];
	$data['billingAddressCity'] = $dados['billingAddressCity'];
	$data['billingAddressState'] = $dados['billingAddressState'];
	$data['billingAddressCountry'] = 'Brasil';
	$data['currency'] = 'BRL';

	for($j=1;$j<$i;$j++){
		$data['itemId'.$j] = $dados['itemId'.$j];
		$data['itemAmount'.$j] = $dados['itemAmount'.$j].'.00';
		$data['itemQuantity'.$j] =$dados['itemQuantity'.$j];
		$data['itemDescription'.$j] = $dados['itemDescription'.$j];
	}

	$data['reference'] = $dados['reference']; //referencia qualquer do produto
	$data['shippingAddressRequired'] = 'false';

	$data = http_build_query($data);
	$url = 'https://ws.pagseguro.uol.com.br/v2/transactions'; //URL de teste


	$curl = curl_init();

	$headers = array('Content-Type: application/x-www-form-urlencoded; charset=ISO-8859-1'
		);

	curl_setopt($curl, CURLOPT_URL, $url . "?email=" . EMAIL_PAGSEGURO);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers );
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true );
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	//curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	curl_setopt($curl, CURLOPT_HEADER, false);
	$xml = curl_exec($curl);

	curl_close($curl);

	$xml= simplexml_load_string($xml);

	//echo $xml -> paymentLink;
	$code =  $xml -> code;
	$date =  $xml -> date;
	
	//aqui eu ja trato o xml e pego o dado que eu quero, vc pode dar um var_dump no $xml e ver qual dado quer

	$retornoCartao = array(
			'code' => $code,
			'date' => $date
	);

	return $retornoCartao;

}

function efetuaPagamentoBoleto($dados,$i) {

	$data['token'] = TOKEN_PAGSEGURO; //token sandbox test
	$data['receiverEmail'] = $dados['receiverEmail'];
	$data['paymentMode'] = 'default';
	$data['senderHash'] = $dados['senderHash'];
	$data['paymentMethod'] = 'boleto';
	$data['senderName'] = $dados['senderName'];
	$data['senderAreaCode'] = $dados['senderAreaCode'];
	$data['senderPhone'] = $dados['senderPhone'];
	$data['senderEmail'] = $dados['senderEmail'];
	if($dados['senderCPF'] != null){$data['senderCPF'] = $dados['senderCPF'];}
	if(isset($dados['senderCNPJ'])){$data['senderCNPJ'] = $dados['senderCNPJ'];}
	$data['currency'] = 'BRL';
	
	for($j=1;$j<$i;$j++){
		$data['itemId'.$j] = $dados['itemId'.$j];
		$data['itemAmount'.$j] = $dados['itemAmount'.$j].'.00';
		$data['itemQuantity'.$j] =$dados['itemQuantity'.$j];
		$data['itemDescription'.$j] = $dados['itemDescription'.$j];
	}
	
	$data['reference'] = $dados['reference'];
	$data['shippingAddressRequired'] = 'false';

	//echo "<p>----------------Dados Informados ao pagSeguro.php------------------</p>";
		
	//var_dump($data);
	
	//echo "<p>----------------Retorno da transação realizada no pagSeguro.php------------------</p>";
	
	$data = http_build_query($data);
	
	$url = 'https://ws.pagseguro.uol.com.br/v2/transactions'; //URL de teste

	$curl = curl_init();

	$headers = array('Content-Type: application/x-www-form-urlencoded; charset=ISO-8859-1');

	curl_setopt($curl, CURLOPT_URL, $url . "?email=" . EMAIL_PAGSEGURO);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers );
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true );
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	//curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	curl_setopt($curl, CURLOPT_HEADER, false);
	$xml = curl_exec($curl);

	curl_close($curl);

	$xml = simplexml_load_string($xml);

	//var_dump($xml);

	//echo $xml -> paymentLink;
	$boletoLink =  $xml -> paymentLink;
	$code =  $xml -> code;
	$date =  $xml -> date;
	
	//aqui eu ja trato o xml e pego o dado que eu quero, vc pode dar um var_dump no $xml e ver qual dado quer

	$retornoBoleto = array(
			'paymentLink' => $boletoLink,
			'date' => $date,
			'code' => $code
	);

	return $retornoBoleto;

}


function notificacao($referencia) {

	$url="https://ws.pagseguro.uol.com.br/v2/transactions?email=".EMAIL_PAGSEGURO."&token=".TOKEN_PAGSEGURO."&reference=".$referencia;

	$curl=curl_init($url);
	curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
	$retorno=curl_exec($curl);
	curl_close($curl);
	$xml=simplexml_load_string($retorno);
	$i = 1;
	foreach($xml->transactions as $transactions){
	    foreach($transactions as $transaction){
	   		echo " 	<tr>
	      			<th scope='row'>{$i}</th>";
	      	echo "<td>{$transaction->date}</td>";
	        echo "<td>
	            <a href='?code={$transaction->code}&operacao=detalhe'>$transaction->code</a></td>
	        ";
	        echo "<td>R$ {$transaction->grossAmount}</td>";
	        echo " 	</tr>";
	        $i++;
	    }
	}
}



function notificacaoAvancada($code) {

	$url="https://ws.pagseguro.uol.com.br/v3/transactions/{$code}?email=".EMAIL_PAGSEGURO."&token=".TOKEN_PAGSEGURO."";

	$curl=curl_init($url);
	curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
	$retorno=curl_exec($curl);
	curl_close($curl);
	$xml=simplexml_load_string($retorno);

	echo "<tr>";
	echo "<th scope='row'>Código</th>";
	echo "<td>".$code."</td></tr>";
	echo "<tr>";
	echo "<th scope='row'>Cliente</th>";
	echo "<td>".$xml->sender->name."</td></tr>";
	foreach ($xml->items as $item) {
	    foreach ($item as $itens) {
	    	echo "<tr>";
	      	echo "<th scope='row'>Descrição</th>";
	        echo "<td>".$itens->description."</td>";
	        echo "</tr>";
	       	echo "<tr>";
	      	echo "<th scope='row'>Quantidade</th>";
	        echo "<td>".$itens->quantity."</td>";
	        echo "</tr>";
	       	echo "<tr>";
	      	echo "<th scope='row'>Valor</th>";
	        echo "<td>R$ ".$itens->amount."</td>";
	        echo "</tr>";	       
	    }
	}
}

$sessao = iniciaPagamentoAction();