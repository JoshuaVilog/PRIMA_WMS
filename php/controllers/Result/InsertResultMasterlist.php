<?php
require_once __DIR__ . '/../../models/ResultModel.php';

// header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $qty = $_POST['qty'];
    $status = $_POST['status'];
    $id = $_POST['id'];

    try {
        $record = new ResultModel();

        $record->qty = $qty;
        $record->status = $status;
        $record->planListID = $id;
        
        $record::InsertResultMasterlist($record);
        echo json_encode(['status' => 'success', 'message' => '']);
        

    } catch (Exception $e){
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }

}