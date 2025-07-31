<?php
require_once __DIR__ . '/../../models/ResultModel.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = $_POST['id'];

    try {

        $records = ResultModel::DisplayResultListRecords($id);
        echo json_encode(['status' => 'success', 'data' => $records]);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }

}
?>
