<?php
session_start();
require_once 'src/db_rep.php';


$db_rep = new DbRepository();
$ordersTable = $db_rep->orders;
$clientsTable = $db_rep->clients;

$type = $_GET['type'] ?? '';
$format = $_GET['format'] ?? 'excel';
$startDate = $_GET['start_date'] ?? date('Y-m-01');
$endDate = $_GET['end_date'] ?? date('Y-m-t');

function exportData($data, $headers, $title, $filename, $format) {
    if ($format == 'word') {
        header("Content-Type: application/vnd.ms-word; charset=UTF-8");
        $filename .= ".doc";
    } else {
        header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
        $filename .= ".xls";
    }
    
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Pragma: no-cache");
    header("Expires: 0");
    
    echo '<!DOCTYPE html>
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>'.htmlspecialchars($title).'</title>
        <style>
            body { font-family: Arial, sans-serif; }
            table { border-collapse: collapse; width: 100%; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
            th { background-color: #4CAF50; color: white; }
            tr:nth-child(even) { background-color: #f2f2f2; }
        </style>
    </head>
    <body>';
    
    echo '<h2>'.htmlspecialchars($title).'</h2>';
    echo '<table>';
    echo '<tr>';
    foreach ($headers as $header) {
        echo '<th>'.htmlspecialchars($header).'</th>';
    }
    echo '</tr>';
    
    if (!empty($data)) {
        foreach ($data as $row) {
            echo '<tr>';
            foreach ($row as $value) {
                echo '<td>'.htmlspecialchars($value).'</td>';
            }
            echo '</tr>';
        }
    }
    
    echo '</table>';
    echo '</body></html>';
    exit();
}

try {
    switch ($type) {
        case 'courier_stats':
            $data = $ordersTable->getDeliveryStatistics($startDate, $endDate);
            $headers = ['№', 'Кол-во заказов', 'Сумма доставок', 'Среднее сумма'];
            
            $exportData = [];
            foreach ($data as $i => $row) {
                $exportData[] = [
                    $i + 1,
                    $row['order_count'],
                    number_format($row['total_amount'], 2) . ' ₽',
				number_format($row['avg_amount'], 2) . ' ₽',
                ];
            }
            
            $title = "Отчёт по курьерам за период " 
                   . date('d.m.Y', strtotime($startDate)) . " - " 
                   . date('d.m.Y', strtotime($endDate));
            exportData($exportData, $headers, $title, "courier_stats_".date('Y-m-d'), $format);
            break;
            
        case 'client_stats':
            $data = $ordersTable->getClientOrderStats($startDate, $endDate);
            $headers = ['№', 'ID клиента', 'Кол-во заказов', 'Средняя сумма', 'Общая сумма'];
            
            $exportData = [];
            foreach ($data as $i => $row) {
                $exportData[] = [
                    $i + 1,
                    $row['client_id'],
                    $row['order_count'],
                    number_format($row['avg_amount'], 2) . ' ₽',
                    number_format($row['total_amount'], 2) . ' ₽'
                ];
            }
            
            $title = "Средняя сумма заказов по клиентам за период " 
                   . date('d.m.Y', strtotime($startDate)) . " - " 
                   . date('d.m.Y', strtotime($endDate));
            exportData($exportData, $headers, $title, "client_stats_".date('Y-m-d'), $format);
            break;

	

        case 'age_stats':
            $data = $clientsTable->getAgeStatistics();
            $headers = ['Возрастная группа', 'Количество клиентов', 'Процент'];
            
            $exportData = [];
            foreach ($data['age_groups'] as $group => $count) {
                $percentage = $data['total_clients'] > 0 
                    ? round(($count / $data['total_clients']) * 100, 2) 
                    : 0;
                
                $exportData[] = [
                    $group,
                    $count,
                    $percentage . '%'
                ];
            }

            $exportData[] = [
                'Всего клиентов',
                $data['total_clients'],
                '100%'
            ];
            
            $title = "Статистика по возрасту клиентов\n"
                   . "Средний возраст: " . round($data['average_age'], 1) . " лет\n"
                   . "Минимальный возраст: {$data['min_age']} лет\n"
                   . "Максимальный возраст: {$data['max_age']} лет";
            
            exportData($exportData, $headers, $title, "age_stats_".date('Y-m-d'), $format);
            break;
            
        default:
            die("Неверный тип отчёта. Допустимые значения: courier_stats, client_stats, age_stats");
    }
} catch (Exception $e) {
    error_log("Export error: " . $e->getMessage());
    die("Произошла ошибка при формировании отчёта. Пожалуйста, попробуйте позже.");
}
?>