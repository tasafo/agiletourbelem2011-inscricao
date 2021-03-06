<?php
session_start();

require_once '../general/autoload.php';

$acao = $_REQUEST['acao'];

if ($acao == 'incluir') {
	$id_tipo_inscricao = $_REQUEST['func_id_tipo_inscricao'];
	$nome = $_REQUEST['func_nome'];
	$email = $_REQUEST['func_email'];
	$cep = $_REQUEST['func_cep'];
	$sexo = $_REQUEST['func_sexo'];

	$o_tipo_inscricao = new TipoInscricaoDAO();
	
    if (!$o_tipo_inscricao->busca($id_tipo_inscricao))
        die("Atencao! Nao foi encontrado o tipo de inscricao no sistema.");
            
	if (!empty($_SESSION['Funcionarios'])) {
		foreach ($_SESSION['Funcionarios'] as $key => $funcionario) {
			if ($nome == $funcionario['func_nome'])
				die("Atencao! O Nome ja esta lancado na lista");

			if ($email == $funcionario['func_email'])
				die("Atencao! O E-mail ja esta lancado na lista");
		}
	}

	$a_campos = array("email");
	foreach($a_campos as $campo) {
		$o_individual = new IndividualDAO();

		if ($o_individual->busca("$campo = '" . $$campo . "'"))
			die("Atencao! Este $campo ja foi utilizando em uma inscricao no sistema.");
	}

	$a_funcionario = array(
        'func_id_tipo_inscricao' => $id_tipo_inscricao,
        'func_nome' => $nome,
        'func_email' => $email,
        'func_cep' => $cep,
        'func_sexo' => $sexo,
        'func_valor_inscricao' => $o_tipo_inscricao->valor
	);

	$_SESSION['Funcionarios'][] = $a_funcionario;

} elseif ($acao == 'excluir') {
	$codigo = $_REQUEST['codigo'];

	unset($_SESSION['Funcionarios'][$codigo]);
}

if (!empty($_SESSION['Funcionarios'])) {
?>
<table border="1" cellpadding="1" cellspacing="1" width="100%">
	<tr>
		<td width="05%" align="center"><b>N.</b></td>
		<td width="40%" align="left"><b>Nome</b></td>
		<td width="40%" align="left"><b>E-mail</b></td>
		<td width="10%" align="right"><b>Valor</b></td>
		<td width="05%" align="center"><b>Excluir</b></td>
	</tr>
	<?php
	$valor_total_inscritos = 0;
	$item = 1;
	foreach ($_SESSION['Funcionarios'] as $key => $funcionario) {
		$valor_total_inscritos += $funcionario['func_valor_inscricao'];
	?>
	<tr>
		<td align="center"><?php echo $item++ ?></td>
		<td align="left"><?php echo $funcionario['func_nome'] ?></td>
		<td align="left"><?php echo $funcionario['func_email'] ?></td>
		<td align="right"><?php echo Funcoes::formata_moeda_para_exibir($funcionario['func_valor_inscricao']) ?></td>
		<td align="center"><a
			onclick="atualizaFuncionarioAjax(<?php echo $key ?>, 'excluir')"><img
			src="images/excluir.gif" /></a></td>
	</tr>
	<?php
	}
	?>
	<tr>
		<td colspan="3"><b>Total</b></td>
		<td align="right"><b><?php echo Funcoes::formata_moeda_para_exibir($valor_total_inscritos) ?></b></td>
		<td>&nbsp;</td>
	</tr>
</table>
<?php
}
?>