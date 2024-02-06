<?php

include 'db_connection.php';


if (isset($_POST['add'])) {
    $scheduleTime = $_POST['schedule_time'];
    $scheduleDate = $_POST['schedule_date'];
    $status = 'vacant'; 

    $stmt = $db->prepare("INSERT INTO schedule (schedule_time, schedule_date, status) VALUES (?, ?, ?)");

   
    if (!$stmt) {
        die('Error: ' . $db->error);
    }

    $stmt->bind_param("sss", $scheduleTime, $scheduleDate, $status);
    $stmt->execute();
    $stmt->close();


    header("Location: ../schedule.php");
    exit();
}


if (isset($_POST['edit'])) {
    $schedule_id = $_POST['schedule_id'];
    $scheduleTime = $_POST['schedule_time'];
    $scheduleDate = $_POST['schedule_date'];
    $status = $_POST['status'];

    $stmt = $db->prepare("UPDATE schedule SET schedule_time=?, schedule_date=?, status=? WHERE schedule_id=?");

    if (!$stmt) {
        die('Error: ' . $db->error);
    }

    $stmt->bind_param("sssi", $scheduleTime, $scheduleDate, $status, $schedule_id);
    $stmt->execute();
    $stmt->close();

    
    header("Location: ../schedule.php");
    exit();
}


if (isset($_GET['delete'])) {
    $schedule_id = $_GET['delete'];


    if (isset($schedule_id)) {
       
        $stmtDeleteAppointments = $db->prepare("DELETE FROM appointments WHERE schedule_id=?");

        if ($stmtDeleteAppointments) {
            $stmtDeleteAppointments->bind_param("i", $schedule_id);
            $stmtDeleteAppointments->execute();
            $stmtDeleteAppointments->close();
            
           
            $stmtDeleteSchedule = $db->prepare("DELETE FROM schedule WHERE schedule_id=?");

            if ($stmtDeleteSchedule) {
                $stmtDeleteSchedule->bind_param("i", $schedule_id);
                $stmtDeleteSchedule->execute();
                $stmtDeleteSchedule->close();
                echo "Schedule deleted successfully!";
            } else {
                
                echo "Error preparing statement for deleting schedule: " . $db->error;
            }
        } else {
            
            echo "Error preparing statement for deleting appointments: " . $db->error;
        }
    } else {
       
        $result = $db->query("DELETE FROM schedule");

        if ($result) {
            echo "All schedules deleted successfully!";
        } else {
     
            echo "Error deleting schedules: " . $db->error;
        }
    }
}




header("Location: ../schedule.php");
exit();
$db->close();



$db->close();
?>
