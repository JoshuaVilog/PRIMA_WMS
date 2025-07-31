<?php
require_once __DIR__ . '/../../models/PlanModel.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $date = $_POST['date'];

    try {
        $id = PlanModel::GetPlanIdByDate($date);
        $records = [];
        
        if($id != null){
            $records = PlanModel::DisplayPlanListRecords($id);
        }
        echo json_encode(['status' => 'success', 'data' => $records]);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }

}
?>
