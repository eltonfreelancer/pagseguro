<?php
global $woocommerce; 
//require_once 'funcoesPGTO.php'; 

$sessao = iniciaPagamentoAction();

echo '
<div class="row-10">
<div class="col-75">
	<div class="container-10">
		<form id="form1" name="form1" method="post" autocomplete="off"
			action="/pagamento-status">
			
			<!--Para evitar autocomplete de dados do form -->
			<input type="text" style="display:none">
			<input type="password" style="display:none">

			<!--Sessão -->
			<input type="text" id="sessao" name="sessao" value="'.$sessao.'">
			<!--Hash-->
			<input type="text" id="hash" name="hash">
			<!--TokenCard-->
			<input type="text" id="tokenCard" name="tokenCard">
			<!--HashCard-->
			<input type="text" id="hashCard" name="hashCard">
			<!--Operação de Pagamento-->
			<input type="text" id="operacao" name="operacao" value="">

			<div class="row-10">
				<div class="col-50">
					<h3>Meus Dados</h3>

					<div class="row-10">
						<div class="col-25">
							<label for="pessoa"> Tipo de Pessoa</label> <select id="pessoa">
								<option value="pj">Pessoa Jurídica</option>
								<option value="pf">Pessoa Física</option>									
							</select>
						</div>

						<div class="col-25">
							
							<div class="pj">

								<label for="cnpjComprador"> CNPJ</label>
								<input type="text" id="cnpjComprador" name="cnpjComprador"
									placeholder="CNPJ" maxlength="18"> 

							</div>
							<div class="pf">
								<label for="cpfComprador">CPF</label>
								<input type="text" id="cpfComprador" name="cpfComprador"
									placeholder="CPF" maxlength="14" required>
							</div>

						</div>
						
					</div>
					
					<div class="row-10">
						<div class="col-25">
							<label for="nomeComprador"> Nome </label>
							<input type="text" id="nomeComprador" name="nomeComprador"
								placeholder="Nome" required> 
						</div>
						<div class="col-25">
							<label for="sobrenomeComprador">    Sobrenome </label> 
							<input type="text"
								id="sobrenomeComprador" name="sobrenomeComprador"
									placeholder="Sobrenome" required> 
						</div>
					</div>
					<div class="row-10">
						<div class="col-50">
							<div class="pj">
								<label for="nomeEmpresa"> Nome da empresa</label> 
								<input type="text" id="nomeEmpresa" name="nomeEmpresa"
									placeholder="Nome da empresa">
							</div>
						</div>
					</div>
					
					<label for="cep">Cep</label> 
					<input type="text" id="cep"
								name="cep" placeholder="CEP" required>
					
					<label for="endereco">Endereço</label>
					<input type="text" id="endereco" name="endereco"
								placeholder="Meu Endereço" required> 
					
					<div class="row-10">
						<div class="col-25">
							<label for="numero">Numero</label> 
							<input type="text" id="numero" name="numero" placeholder="Numero"
								required> 
						</div>
						<div class="col-25">
							<label for="complemento">Complemento</label> 
							<input type="text" id="complemento" name="complemento" placeholder="complemento"
								required> 
						</div>
					</div>
					
					<div class="row-10">
						<div class="col-25">
							<label for="bairro">Bairro</label> 
							<input type="text" id="bairro" name="bairro"
								placeholder="Bairro" required> 
						</div>
						<div class="col-25">
							<label for="cidade">Cidade</label> 
							<input type="text"
								id="cidade" name="cidade" placeholder="Cidade" required>
						</div>
					</div>
						
					<div class="row-10">
						<div class="col-25">
							<label for="pais">País</label>
							<input type="text"
								id="pais" name="pais" value="Brasil">
						</div>
						<div class="col-25">
							<label for="uf">Estado</label> '; 
							
					$options = "<select id='uf'	name='uf' required='true'>
								<option value=''>Selecione</option>"; 
								
					$estadosBrasileiros = array( 'AC'=>'Acre', 'AL'=>'Alagoas', 'AP'=>'Amapá',
								'AM'=>'Amazonas', 'BA'=>'Bahia', 'CE'=>'Ceará', 'DF'=>'Distrito
								Federal', 'ES'=>'Espírito Santo', 'GO'=>'Goiás',
								'MA'=>'Maranhão', 'MT'=>'Mato Grosso', 'MS'=>'Mato Grosso do
								Sul', 'MG'=>'Minas Gerais', 'PA'=>'Pará', 'PB'=>'Paraíba',
								'PR'=>'Paraná', 'PE'=>'Pernambuco', 'PI'=>'Piauí', 'RJ'=>'Rio
								de Janeiro', 'RN'=>'Rio Grande do Norte', 'RS'=>'Rio Grande do
								Sul', 'RO'=>'Rondônia', 'RR'=>'Roraima', 'SC'=>'Santa
								Catarina', 'SP'=>'São Paulo', 'SE'=>'Sergipe',
								'TO'=>'Tocantins' ); 
								
					foreach ($estadosBrasileiros as $uf => $estado) { 
									$selected = $uf == $myUF ?"selected":""; 
									$options .= "<option $selected value='{$uf}'>{$estado}</option>"; } 
									$options .= "</select>"; 
					echo $options;
					
echo '
							</div>					
						</div>
						
						<div class="row-10">
							<div class="col-25">
								<label for="telefoneComprador"> Telefone</label>
								<input type="text" id="telefoneComprador" name="telefoneComprador"
									maxlength="10" required>
							</div>
							<div class="col-25">
								<label for="celularComprador"> Celular</label>
								<input type="text" id="celularComprador" name="celularComprador"
									maxlength="10">
							</div>
						</div>
						
						<label for="email">	E-mail</label> 
						<input type="text"
							id="email" name="email" placeholder="email@exemplo.com" required>

						<label for="observacao"> Observação</label> 
						<textarea type="text"
							id="observacao" name="observacao" rows="3">	</textarea>

					</div>

					<div class="col-50">
						<h3>
							Carrinho <span class="price" style="color: black">
						
						</h3>
						<table>
							<thead>
								<th>Descriçao</th>
								<th>Quantidade</th>
								<th>Valor Unitário</th>
								<th>Valor Total</th>
							</thead>
							<tbody>
								';  
								$valorFinal = 0; 
								//Listando produtos do carrinho 
								$items =
								$woocommerce->cart->get_cart(); foreach($items as $item =>
								$values) { $_product = wc_get_product(
								$values['data']->get_id()); $price =
								get_post_meta($values['product_id'] , '_price', true); echo '
								<tr>
									'; echo '
									<td><b>'.$_product->get_title().'</b></td>'; echo '
									<td><label>'.$values['quantity'].'</label></td>'; echo '
									<td>Por: R$'.$price.',00</td>'; echo '
									<td><span class=\"price\">R$ '.$values['quantity'] *
											$price.',00</span></td>'; echo '
								</tr>
								'; $valorFinal = $valorFinal + ($values['quantity'] * $price); }
								
echo '
								<tr>
									<td colspan="3"><b>SUBTOTAL</b></td>
									<td><b> R$'.$valorFinal .',00</b> <input type="hidden"
										id="valorFinal" name="valorFinal" value="'.$valorFinal .'.00">
									</td>
								
								
								<tr>'; 
echo '
							
							</tbody>
						</table>
						'; 
echo '
						<h3>Pagamento</h3>

						<div class="tab-100">

							<a class="btn-primary-100 tablinks" onclick="abrirPGTO(event, "
								cartao")" id="defaultOpen">Cartão de Crédito</a> <a
								class="btn-primary-100 tablinks" onclick="abrirPGTO(event, "debito")">Débito
								Online</a> <a class="btn-primary-100 tablinks"
								onclick="abrirPGTO(event, "boleto")">Boleto Bancário</a>

						</div>

						<div id="cartao" class="tabcontent-100">
							<label for="fname">Cartões Aceitos</label>
							<div class="icon-container">
								<i class="fa fa-cc-visa"></i> <i class="fa fa-cc-amex"></i> <i
									class="fa fa-cc-mastercard"></i> <i class="fa fa-cc-discover"></i>
							</div>
							<label for="nomeCartao">Nome do portador (como gravado no cartão)
								*</label> <input type="text" id="nomeCartao" name="nomeCartao"
								placeholder="Nome do portador"> <label for="cpfCartao">CPF do
								Titular do Cartão *</label> <input type="text" id="cpfCartao"
								name="cpfCartao" placeholder="CPF do Titular do Cartão"> <label
								for="numeroCartao">Número do cartão <span class="bandeiraCartao"></span></label>
							<input type="text" id="numeroCartao" name="numeroCartao"
								placeholder="▪▪▪▪ ▪▪▪▪ ▪▪▪▪ ▪▪▪▪">
							<div class="row-10">
								<div class="col-50">
									<label for="mesValidade">Mês (MM)* </label> <input type="text"
										id="mesValidade" name="mesValidade" placeholder="MM">
								</div>
								<div class="col-50">
									<label for="anoValidade">Ano (AAAA)* </label> <input
										type="text" id="anoValidade" name="anoValidade"
										placeholder="AAAA">
								</div>
								<div class="col-50">
									<label for="cvv">Código de segurança</label> <input type="text"
										id="cvv" name="cvv" placeholder="CVV">
								</div>
								<div class="col-50">
									<label for="qtdParcelas">Parcelas</label> <select
										name="qtdParcelas" id="qtdParcelas">
										<option value="">Selecione</option>
									</select> <input type="hidden" id="valorParcelas"
										name="valorParcelas">
								</div>
							</div>
						</div>

						<div id="debito" class="tabcontent-100">
							<h3>Débito Online</h3>
							<p>Selecione o seu banco:</p>
						</div>

						<div id="boleto" class="tabcontent-100">
							<h3>Boleto Bancário</h3>
							<p>O pedido será confirmado apenas após a confirmação do
								pagamento. Taxa: R$ 1,00 (taxa aplicada para cobrir custos de
								gestão de risco do meio de pagamento). * Após clicar em
								"Realizar pagamento" você receberá o seu boleto bancário, é
								possível imprimi-lo e pagar pelo site do seu banco ou por uma
								casa lotérica.</p>
						</div>

					</div>
				</div>

				<input type="submit"
					value="Continue para concluir a compra" class="btn-100">
			</form>
		</div>
	</div>
';
echo '</div>';