<?php
require_once __DIR__ . '/../../config/db.php';

class ResultModel {

    public static function InsertResultMasterlist($plan){
        $db = DB::connectionWMS();
        $userCode = $_SESSION['USER_CODE'];

        $planListID = $db->real_escape_string($plan->planListID);
        $qty = $db->real_escape_string($plan->qty);
        $status = $db->real_escape_string($plan->status);

        $sql = "INSERT INTO `result_masterlist`(
            `RID`,
            `PLAN_LIST_ID`,
            `QTY`,
            `STATUS`,
            `CREATED_BY`
        )
        VALUES(
            DEFAULT,
            '$planListID',
            '$qty',
            '$status',
            '$userCode'
        )";
        return $db->query($sql);
    }
    public static function DisplayResultListRecords($id) {
        $db = DB::connectionWMS();
        $sql = "SELECT `RID`, `QTY`, `STATUS`, `CREATED_AT`, `CREATED_BY` FROM `result_masterlist` WHERE CONCAT(PLAN_LIST_ID, COALESCE(DELETED_AT, '')) = '$id' ORDER BY RID DESC";
        $result = $db->query($sql);

        $records = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $records[] = $row;
            }
        }

        return $records;
    }
}