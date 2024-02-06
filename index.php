<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/mainstyle.css">
    <title >Dashboard</title>
  
</head>
<body>

<div class="container">
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
            max-width: 600px;
            margin: 20px;
            padding: 20px;
            background-color: #fff;
            
        }
        .button {
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
th {
    background-color:lightblue;
}
    </style>
    <h2 >Dashboard</h2>

    
    <a href="patient.php" class="button">Manage Patients</a>
    <a href="schedule.php" class="button">Manage Schedules</a>
    <a href="appointment.php" class="button">Manage Appointments</a>


    <h2>Appointments</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Patient Name</th>
                <th>Schedule Date</th>
                <th>Schedule Time</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
          
            include 'include/db_connection.php';

       
            $sqlAppointments = "SELECT appointments.id, patients.name AS patient_name, schedule.schedule_date, schedule.schedule_time, appointments.status FROM appointments
            INNER JOIN patients ON appointments.patient_id = patients.patient_id
            INNER JOIN schedule ON appointments.schedule_id = schedule.schedule_id";

            $resultAppointments = $db->query($sqlAppointments);

            if ($resultAppointments === FALSE) {
                die("Error executing the query: " . $db->error);
            }

            if ($resultAppointments->num_rows > 0) {
                while ($row = $resultAppointments->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['id']}</td>";
                    echo "<td>{$row['patient_name']}</td>";
                    echo "<td>{$row['schedule_date']}</td>";
                    echo "<td>{$row['schedule_time']}</td>";
                    echo "<td>{$row['status']}</td>";
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
