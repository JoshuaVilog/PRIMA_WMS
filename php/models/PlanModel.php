<?php
require_once __DIR__ . '/../../config/db.php';

class PlanModel {

    public static function DisplayPlanRecords() {
        $db = DB::connectionWMS();
        $sql = "SELECT `RID`, `DATE`, `CREATED_AT`, `CREATED_BY` FROM `plan_masterlist` WHERE COALESCE(DELETED_AT, '') = '' ORDER BY RID DESC";
        $result = $db->query($sql);

        $records = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $records[] = $row;
            }
        }

        return $records;
    }
    public static function CheckDuplicateDate($date){
        $db = DB::connectionWMS();

        $sql = "SELECT RID FROM plan_masterlist WHERE CONCAT(DATE, COALESCE(DELETED_AT, '')) = '$date'";
        $result = mysqli_query($db,$sql);

        if(mysqli_num_rows($result) == 0){
            return false;
        } else {
            return true;
        }
    }
    public static function InsertPlanMasterlist($records){
        $db = DB::connectionWMS();
        $userCode = $_SESSION['USER_CODE'];

        $date = $db->real_escape_string($records->date);

        $sql = "INSERT INTO `plan_masterlist`(
            `RID`,
            `DATE`,
            `CREATED_BY`
        )
        VALUES(
            DEFAULT,
            '$date',
            '$userCode'
        )";
        $db->query($sql);
        
        return $db->insert_id;
    }
    public static function InsertPlanList($plan){
        $db = DB::connectionWMS();
        $userCode = $_SESSION['USER_CODE'];

        $planID = $db->real_escape_string($plan->planID);
        $item = $db->real_escape_string($plan->item);
        $process = $db->real_escape_string($plan->process);
        $qty = $db->real_escape_string($plan->qty);

        $sql = "INSERT INTO `plan_list`(
            `RID`,
            `PLAN_ID`,
            `ITEM`,
            `PROCESS`,
            `QTY`
        )
        VALUES(
            DEFAULT,
            '$planID',
            '$item',
            '$process',
            '$qty'
        )";
        return $db->query($sql);
    }
    public static function GetPlanIdByDate($date){
        $db = DB::connectionWMS();

        $sql = "SELECT RID FROM plan_masterlist WHERE CONCAT(DATE, COALESCE(DELETED_AT, '')) = '$date'";
        $result = mysqli_query($db,$sql);

        if(mysqli_num_rows($result) == 0){
            return null;
        } else {
            $row = mysqli_fetch_assoc($result);
            return $row['RID'];
        }
    }
    public static function DisplayPlanListRecords($id) {
        $db = DB::connectionWMS();
        $sql = "SELECT `RID`, `ITEM`, `PROCESS`, `QTY`, `CREATED_AT` FROM `plan_list` WHERE CONCAT(PLAN_ID, COALESCE(DELETED_AT, '')) = '$id' ORDER BY RID DESC";
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