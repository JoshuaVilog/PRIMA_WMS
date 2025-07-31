<?php
require_once __DIR__ . '/../../models/ProcessModel.php';

// header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = $_POST['id'];

    try {
       $record = ProcessModel::RemoveRecord($id);

       echo json_encode($record);

    } catch (Exception $e){
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }

}