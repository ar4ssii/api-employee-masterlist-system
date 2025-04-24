<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

require 'db.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method == "GET") {

    // with id
    $path = explode("/", $_SERVER['REQUEST_URI']);
    if (isset($path[4]) && is_numeric($path[4])) {
        $json_array = array();
        $employee_id = $path[4];

        $sql = "SELECT * FROM tbl_employee WHERE employee_id = $employee_id";
        $result = $conn->query($sql);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $json_array['employeedata'][] = array(
                    'id' => $row['employee_id'],
                    'employee_code' => $row['employee_code'],
                    'employee_lname' => $row['employee_lname'],
                    'employee_fname' => $row['employee_fname'],
                    'employee_gender' => $row['employee_gender'],
                    'employee_birthday' => $row['employee_birthday'],
                    'employee_address' => $row['employee_address']
                );
            }
            echo json_encode($json_array['employeedata']);
        } else {
            echo json_encode([]);
        }
    } else {
        // without id
        $sql = "SELECT * FROM tbl_employee";
        $result = $conn->query($sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $json_array['employeedata'][] = array(
                    'id' => $row['employee_id'],
                    'employee_code' => $row['employee_code'],
                    'employee_lname' => $row['employee_lname'],
                    'employee_fname' => $row['employee_fname'],
                    'employee_gender' => $row['employee_gender'],
                    'employee_birthday' => $row['employee_birthday'],
                    'employee_address' => $row['employee_address']
                );
            }
            echo json_encode($json_array['employeedata']);
        } else {
            echo json_encode([]);
        }
    }
} elseif ($method == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    $employee_code = $data['employee_code'];
    $employee_lname = $data['employee_lname'];
    $employee_fname = $data['employee_fname'];
    $employee_birthday = $data['employee_birthday'];
    $employee_gender = $data['employee_gender'];
    $employee_address = $data['employee_address'];

    $sql = "INSERT INTO 
    tbl_employee (employee_code, employee_lname, employee_fname, employee_birthday, employee_gender, employee_address) 
    VALUES ('$employee_code', '$employee_lname', '$employee_fname', '$employee_birthday', '$employee_gender', '$employee_address')";

    $sql_result = mysqli_query($conn, $sql);

    if ($sql_result) {
        echo json_encode(array("status" => "success", "message" => "Employee added successfully."));
    } else {
        echo json_encode(array("message" => "Error adding employee: " . mysqli_error($conn)));
    }
} elseif ($method == "PUT") {
    $data = json_decode(file_get_contents("php://input"), true);

    $employee_id = $data['employee_id'];
    $employee_code = $data['employee_code'];
    $employee_lname = $data['employee_lname'];
    $employee_fname = $data['employee_fname'];
    $employee_birthday = $data['employee_birthday'];
    $employee_gender = $data['employee_gender'];
    $employee_address = $data['employee_address'];

    $sql = "UPDATE tbl_employee SET employee_code='$employee_code', employee_lname='$employee_lname', employee_fname='$employee_fname', employee_birthday='$employee_birthday', employee_gender='$employee_gender', employee_address='$employee_address' WHERE employee_id=$employee_id";
    $sql_result = mysqli_query($conn, $sql);

    if ($sql_result) {
        echo json_encode(array("status" => "success", "message" => "Employee Info Updated Successfully."));
    } else {
        echo json_encode(array("message" => "Error updating employee info: " . mysqli_error($conn)));
    }
}elseif($method == "DELETE"){
    $path = explode("/", $_SERVER['REQUEST_URI']);

    $sql = "DELETE FROM tbl_employee WHERE employee_id = $path[4]";
    $sql_result = mysqli_query($conn, $sql);
    if ($sql_result) {
        echo json_encode(array("status" => "success", "message" => "Employee Deleted Successfully."));
    } else {
        echo json_encode(array("message" => "Error deleting employee: " . mysqli_error($conn)));
    }
}
