<?php 
    include "../../functions/main_config.php";
    include "service.php";
    
    $ReportService = new ReportService;

    $schedules_by_user = [];
    $report_name = "Relatório de Agendamentos";
    $report_data = $ReportService->reportSchedule();
    $total_schedules = $data_db->linesAccount($report_data);
    
    while ($row = $data_db->getLine($report_data)) {
        $codigo_usuario = $row["codigoUsuario"];
        
        if (!isset($schedules_by_user[$codigo_usuario])) {
            $schedules_by_user[$codigo_usuario] = [
                'id' => $row['id'],
                'nomeUsuario' => $row['nomeUsuario'],
                'schedules' => [],
            ];
        }
        $schedules_by_user[$codigo_usuario]['schedules'][] = $row;
    };
?>

<div class="report">
    <div class="report-header d-flex">
        <?php include "../../components/basic/header_report.php"; ?>
    </div>

    <div class="report-body">
        <?php if($total_schedules > 0) { ?>
            <?php foreach ($schedules_by_user as $codigo_usuario => $schedules) { ?>
                <?php $total_user = count($schedules['schedules']); $nome_usuario = $schedules['nomeUsuario']; ?>
                
                <h6 class="grouper-print">
                    <span class="font-weight-bold"><?= ucfirst("Usuário"); ?>: </span><?= $nome_usuario == 'ADM' ? strtoupper($nome_usuario) : ucfirst(strtolower($nome_usuario));?>
                    <span class="float-end"><span class="font-weight-bold">Total</span>: <?= number_format($total_user, 0, ",", "."); ?></span>
                </h6>

                <table class="table table-report-print table-hover">
                    <thead>
                        <tr class="tr-header-row">
                            <th class="font-weight-bold text-center" width="70"  >Nº AG</th>
                            <th class="font-weight-bold text-center" width="150" >Data e Hora</th>
                            <th class="font-weight-bold text-left"   width="100" >Período</th>
                            <th class="font-weight-bold text-left"               >Título</th>
                            <th class="font-weight-bold text-center" width="150" >Atendimento</th>
                            <th class="font-weight-bold text-center" width="50"  >Atendido</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        <?php foreach ($schedules['schedules'] as $schedule) { ?>
    
                            <tr class="tr-collapse-row" data-bs-toggle="collapse" data-bs-target="#collapse_row_<?= $schedule["id"]; ?>" aria-expanded="false" aria-controls="collapse_row_<?= $schedule["id"]; ?>">
                                <td class="text-center"><?=   $schedule["id"]; ?></td>
                                <td class="text-center"><?=   $schedule["dataHoraAgendamento"] ? dateHourBR($schedule["dataHoraAgendamento"]) : "N/I"; ?></td>
                                <td class="text-left"  ><?=   $schedule["descricao"] ? $schedule["descricao"] : "-"; ?></td>
                                <td class="text-left"  ><span class="font-size-12 <?= $schedule["obsPendente"]; ?>"><?= $schedule["obsPendente"]; ?></span></td>
                                <td class="text-center"><?=   (($schedule["dataHoraAtendimento"]) == null) ? "" : dateHourBR($schedule["dataHoraAtendimento"])?></td>
                                <td class="text-center"><span class="font-size-11 badge <?= TYPE_SERVED[$schedule["atendido"]]["badge"]; ?>"><?= TYPE_SERVED[$schedule["atendido"]]["texto"]; ?></span></td>
                                
                                <!-- Linha colapsável -->
                                <tr class="table-white collapse" id="collapse_row_<?= $schedule["id"]; ?>">
                                    <td colspan="7">
                                        <table class="table table-report-print py-0 my-0">
                                            <tr class="table-white">
                                                <td class="text-left font-size-12 border-0">
                                                    <?= $schedule["obsAtendimento"] ? $schedule["obsAtendimento"] : "Não existe nenhuma observação de atendimento registrada."; ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>                   
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } ?>
        <?php } else { ?>
            <table class="table table-report-print">
                <tbody>
                    <tr><td width="10%" colspan="100" class="text-left">Nenhum registro encontrado.</td></tr>
                </tbody>
            </table>
        <?php } ?>
        <h6 class="grouper-print-total">
            <span class="float-end"><span class="font-weight-bold">Total Geral</span>: <?= number_format($total_schedules, 0, ",", "."); ?></span>
        </h6>
    </div>
</div>