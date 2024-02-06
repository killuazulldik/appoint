<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/mainstyle.css">
    
    <title>Schedule Form</title>
    <style>
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
th {
    background-color:lightblue;
}
    </style>
</head>
<body>
    <div class="container">
        
        <h2>Schedule Form</h2>

        <?php
      
        include 'include/db_connection.php';

       
        if (isset($_GET['edit'])) {
           
            $db = new mysqli($servername, $username, $password, $database);

          

            $editId = $_GET['edit'];
            $editResult = $db->query("SELECT * FROM schedule WHERE schedule_id=$editId");

            if ($editResult !== false && $editResult->num_rows == 1) {
                $editRow = $editResult->fetch_assoc();
                ?>
            
                <h2>Edit Schedule</h2>
                <form action="include/schedule_process.php" method="post">
                    <input type="hidden" name="schedule_id" value="<?php echo $editRow['schedule_id']; ?>">
                    <label for="schedule_time">Schedule Time:</label>
                    <input type="time" name="schedule_time" value="<?php echo $editRow['schedule_time']; ?>" required>
                    <label for="schedule_date">Schedule Date:</label>
                    <input type="date" name="schedule_date" value="<?php echo $editRow['schedule_date']; ?>" required>
                    <label for="status">Status:</label>
                    <select name="status" required>
                        <option value="vacant" <?php echo ($editRow['status'] == 'vacant') ? 'selected' : ''; ?>>Vacant</option>
                        <option value="occupied" <?php echo ($editRow['status'] == 'occupied') ? 'selected' : ''; ?>>Occupied</option>
                    </select>
                    <button type="submit" name="edit">Update</button>
                </form>
                <?php

           
                $db->close();
                exit();
            }

         
            $db->close();
        } else {
            
            ?>
          
            <form action="include/schedule_process.php" method="post">
                <label for="schedule_time">Schedule Time:</label>
                <input type="time" name="schedule_time" required>
                <label for="schedule_date">Schedule Date:</label>
                <input type="date" name="schedule_date" required>

                <div style="display: flex; align-items: center; margin-top: 10px;">
                <div style="display: flex; align-items: center; margin-top: 10px;">
    <button type="submit" name="add" style="width: 100px;background-color: blue; height: 40px; margin-right: 10px;">Add</button>
    <a href="index.php" class="button" style="width: 100px;background-color: blue; height: 40px; margin-right: 10px; text-align: center;">Back</a>
</div>

</div>

            </form>
            <?php
        }
        ?>

        <h2>Schedules</h2>

        <?php
     
        $db = new mysqli($servername, $username, $password, $database);

       

        $result = $db->query("SELECT * FROM schedule");

        if ($result !== false && $result->num_rows > 0) {
            echo '<table>';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Schedule Time</th>';
            echo '<th>Schedule Date</th>';
            echo '<th>Status</th>';
            echo '<th>Action</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo "<td>{$row['schedule_time']}</td>";
                echo "<td>{$row['schedule_date']}</td>";
                echo "<td>{$row['status']}</td>";
                echo '<td>';
                echo "<a href='schedule.php?edit={$row['schedule_id']}' class='button_edit'>Edit</a>";
                echo " | ";
                echo "<a href='include/schedule_process.php?delete={$row['schedule_id']}' class='button_delete'>Delete</a>";
                echo '</td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
        } else {
            echo '<p>No schedules found.</p>';
        }

        if ($result !== false) {
            $result->free();
        }

      
        $db->close();
        ?>
    </div>
</body>
</html>
