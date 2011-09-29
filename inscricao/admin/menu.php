<?php
require 'validaSessao.php';
require_once '../general/autoload.php';

$a_menu = array(
    'relatorioIndividual' => 'Inscrições Individuais',
    'relatorioEmpresas' => 'Inscrições por Empresa',
    'relatorioInscritos' => 'Listagem dos Inscritos Pagantes',
    'relatoriosTela' => 'Outros Relatórios',
    'manutencaoInscritos' => 'Manutenção dos Inscritos',
    'index' => 'Sair'
);

$o_inscricao = new InscricaoDAO();
$a_em_aberto = $o_inscricao->valor_total_inscritos("A");
$a_confirmados = $o_inscricao->valor_total_inscritos("C");
$a_incritos_instituicao = $o_inscricao->total_de_inscritos_por_instituicao();
?>
<!DOCTYPE html>
<html lang="pt-br">
  <head>
        <meta charset="utf-8">
        <title>Área Administrativa</title>
        <link href="css/admin.css" rel="stylesheet" />
    </head>
    <body>
        <center>
            <table>
                <tr>
                    <td align="center"><b>Área Administrativa</b></td>
                </tr>
                
                <?php foreach ($a_menu as $arquivo => $opcao) { ?>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td><a href="<?php echo $arquivo ?>.php"><?php echo $opcao ?></a></td>
                </tr>
                <?php } ?>
            </table>
        </center>
        <br><hr>
        <b>Totais de inscrições</b><br><br>
        <b><?php echo $a_em_aberto[0]->quantidade ?></b> inscrições em <b>aberto</b> no valor de <b>R$ <?php echo Funcoes::formata_moeda_para_exibir($a_em_aberto[0]->valor) ?></b>
        <br><br>
        <b><?php echo $a_confirmados[0]->quantidade ?></b> inscrições <b>confirmadas</b> no valor de <b>R$ <?php echo Funcoes::formata_moeda_para_exibir($a_confirmados[0]->valor) ?></b>
        <br><br>
        <b><?php echo $a_em_aberto[0]->quantidade + $a_confirmados[0]->quantidade ?></b> inscrições realizadas no total
        
        <br><br><hr>
        <b>Inscritos por Instituição</b><br><br>
        <table border="1" class="bordasimples">
            <tr>
                <th>Instituição</th>
                <th>Inscritos</th>
            </tr>
        <?php
        $total_inscritos = 0;
        foreach($a_incritos_instituicao as $inscritos) {
            $total_inscritos += $inscritos->quantidade;
        ?>
            <tr>
                <td><?php echo utf8_encode($inscritos->instituicao) ?></td>
                <td align="center"><?php echo $inscritos->quantidade ?></td>
            </tr>
        <?php
        }
        ?>
        <tr>
            <td><b>Total</b></td>
            <td align="center"><b><?php echo $total_inscritos ?></b></td>
        </tr>
        </table>
    </body>
</html>