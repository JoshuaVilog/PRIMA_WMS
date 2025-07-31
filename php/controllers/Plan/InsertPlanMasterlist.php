<?php
require_once __DIR__ . '/../../models/PlanModel.php';

// header('Content-Type: application/json');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    $date = $data['date'];
    $processList = $data['processList'];

    try {
        $plan = new PlanModel();

        $plan->date = $date;
        
        $isDuplicate = $plan::CheckDuplicateDate($date);

        if($isDuplicate == true){

            echo json_encode(['status' => 'duplicate', 'message' => '']);
        } else if($isDuplicate == false){

            $planID = $plan::InsertPlanMasterlist($plan);

            foreach($processList as $row){
                $plan->planID = $planID;
                $plan->item = $row['item'];
                $plan->process = $row['process'];
                $plan->qty = $row['qty'];

                $plan::InsertPlanList($plan);
            }

            echo json_encode(['status' => 'success', 'message' => '']);
        }

    } catch (Exception $e){
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }

}