<?php
include 'include/db_connection.php';

$sqlPatients = "SELECT patient_id, name FROM patients";
$resultPatients = $db->query($sqlPatients);
$patients = $resultPatients->fetch_all(MYSQLI_ASSOC);


$sqlSchedule = "SELECT schedule_id, schedule_time, schedule_date FROM schedule";
$resultSchedule = $db->query($sqlSchedule);
$schedules = $resultSchedule->fetch_all(MYSQLI_ASSOC);


$sqlAppointments = "SELECT id, patient_id, schedule_id, status FROM appointments";
$resultAppointments = $db->query($sqlAppointments);


if ($resultAppointments === FALSE) {
    die("Error executing the query: " . $db->error);
}

if (isset($_POST['edit'])) {
    $editAppointmentId = $_POST['edit_appointment_id'];
    $editPatientId = $_POST['edit_patient_id'];
    $editScheduleId = $_POST['edit_schedule_id'];
    $editStatus = $_POST['edit_status'];

    $editQuery = "UPDATE appointments 
                  SET patient_id = '$editPatientId', 
                      schedule_id = '$editScheduleId', 
                      status = '$editStatus' 
                  WHERE id = '$editAppointmentId'";

    $resultEdit = $db->query($editQuery);

    if ($resultEdit === TRUE) {
        echo "Appointment updated successfully!";
    } else {
        echo "Error updating appointment: " . $db->error;
    }

    header("Location: appointment.php");
    exit();
}


if (isset($_GET['delete'])) {
    $deleteAppointmentId = $_GET['delete'];

    $deleteQuery = "DELETE FROM appointments WHERE id = '$deleteAppointmentId'";
    $resultDelete = $db->query($deleteQuery);

    if ($resultDelete === TRUE) {
        echo "Appointment deleted successfully!";
    } else {
        echo "Error deleting appointment: " . $db->error;
    }

    header("Location: appointment.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/mainstyle.css">
    
    <title>Appointment Form</title>
    
</head>
<body>
<style>
        body {
            font-family: Arial, sans-serif;
        
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center; 
        }

        .container {
            max-width: 800px; 
            margin: 20px;
            padding: 20px;
            background-color: #fff; 
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h2 {
            margin-bottom: 10px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 10px; 
            background-color: #fff; 
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
           
        }

        th {
            background-color: lightblue;
        }

        .buttons{
            display: inline-block;
            padding: 10px;
            text-decoration: none;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 5px;
        }

        .button:hover {
            background-color: blue;
        }
            .button_edit{  
        display: inline-block;
        padding: 10px;
        text-decoration: none;
        background-color:blue;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        margin: 10px;

        }
        .button_delete{    
        display: inline-block;
        padding: 10px;
        text-decoration: none;
        background-color:red;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        margin: 10px;

        }
    </style>
<div class="container">
    <h2>Appointment Form</h2>

   
    <form action="include/appointment_process.php" method="post">
        <input type="hidden" name="id" id="id">

        <label for="patient_id">Patient Name:</label>
        <select name="patient_id" id="patient_id" required>
            <?php
            foreach ($patients as $patient) {
                echo "<option value='{$patient['patient_id']}'>{$patient['name']}</option>";
            }
            ?>
        </select>

        <label for="schedule_id">Schedule Time:</label>
        <select name="schedule_id" id="schedule_id" required>
            <?php
            foreach ($schedules as $schedule) {
                echo "<option value='{$schedule['schedule_id']}'>{$schedule['schedule_time']}</option>";
            }
            ?>
        </select>

        <label for="schedule_date">Schedule Date:</label>
        <select name="schedule_date" id="schedule_date" required>
            <?php
            foreach ($schedules as $schedule) {
                echo "<option value='{$schedule['schedule_id']}'>{$schedule['schedule_date']}</option>";
            }
            ?>
        </select>

        <label for="status">Status:</label>
        <select name="status" id="status" required>
            <option value="Pending">Pending</option>
            <option value="Confirmed">Confirmed</option>
            <option value="Cancelled">Cancelled</option>
        </select>

        
        <div class="buttons">
            <button type="submit" style =" background-color: blue;" name="add">Add Appointment</button>
            <a href="index.php" class="button" style=" background-color: blue; color: white; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer;"> Back </a>
        </div>
    </form>
    </div>
    
    <div class= "container" >
    <h2>Appointments</h2>
    
    <table>
        <thead>
            <tr>
                <th>Patient Name</th>
                <th>Schedule Date</th>
                <th>Schedule Time</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($resultAppointments->num_rows > 0) {
                while ($row = $resultAppointments->fetch_assoc()) {
                  
                    $patientId = $row['patient_id'];
                    $scheduleId = $row['schedule_id'];

                  
                    $patientQuery = "SELECT name FROM patients WHERE patient_id = $patientId";
                    $resultPatient = $db->query($patientQuery);
                    $patient = $resultPatient->fetch_assoc();

                   
                    $scheduleQuery = "SELECT schedule_time, schedule_date FROM schedule WHERE schedule_id = $scheduleId";
                    $resultSchedule = $db->query($scheduleQuery);
                    $schedule = $resultSchedule->fetch_assoc();

                    echo "<tr>";
                    echo "<td>{$patient['name']}</td>";
                    echo "<td>{$schedule['schedule_date']}</td>";
                    echo "<td>{$schedule['schedule_time']}</td>";
                    echo "<td>{$row['status']}</td>";
                    echo "<td>";
                    echo "<form action='include/appointment_process.php' method='post'>";
                    echo "<a href='edit.php?id={$row['id']}' class='button_edit'>Edit</a> | <a href='include/appointment_process.php?delete={$row['id']}' class='button_delete'>Delete</a>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No appointments found</td></tr>";
            }
            ?>
        </tbody>
    </table>
    </div>

</body>
</html>
