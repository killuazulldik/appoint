    <?php

    include 'include/db_connection.php';

    
    $editMode = false;
    $editId = '';
    $editName = '';
    $editEmail = '';


    if (isset($_GET['edit'])) {
        $editId = $_GET['edit'];
        $editMode = true;

  
        $result = $db->query("SELECT * FROM patients WHERE patient_id = $editId");

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $editName = $row['name'];
            $editEmail = $row['email'];
        }
    }

    if (isset($_POST['add'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];

        $stmt = $db->prepare("INSERT INTO patients (name, email) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $email);
        $stmt->execute();
        $stmt->close();

        header("Location: patient.php");
        exit();
    }

   
    if (isset($_POST['edit'])) {
        $id = $_POST['patient_id']; 
        $name = $_POST['name'];
        $email = $_POST['email'];

        $stmt = $db->prepare("UPDATE patients SET name=?, email=? WHERE patient_id=?");
        $stmt->bind_param("ssi", $name, $email, $id);
        $stmt->execute();
        $stmt->close();

        
        header("Location: patient.php");
        exit();
    }

   
    $result = $db->query("SELECT * FROM patients");

    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/mainstyle.css">
        <title>Patient Form</title>
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
            <?php if (!$editMode): ?>
                <h2>Patient Form</h2>
             
                <form action="patient.php" method="post">
                    <label for="name">Name:</label>
                    <input type="text" name="name" required>
                    <label for="email">Email:</label>
                    <input type="email" name="email" required>

                    
                    <button type="submit" name="add" style="width: 100px;  background-color: blue; height: 40px; margin-right: 10px;">ADD</button>
<a href="index.php" class="button"  style="width: 100px;  background-color: blue; height: 40px; margin-right: 10px; text-align:center;">BACK</a>


                </form>

            <?php endif; ?>

            <?php if ($editMode): ?>
             
                <h2>Edit Patient</h2>
                <form action="patient.php" method="post">
                    <input type="hidden" name="patient_id" value="<?php echo $editId; ?>">
                    <label for="name">Name:</label>
                    <input type="text" name="name" value="<?php echo $editName; ?>" required>
                    <label for="email">Email:</label>
                    <input type="email" name="email" value="<?php echo $editEmail; ?>" required>
                    <button type="submit" name="edit">Edit</button>
                </form>
            <?php endif; ?>

         
            <h2>Patients</h2>

            <?php
            if ($result->num_rows > 0) {
                echo '<table>';
                echo '<thead>';
                echo '<tr>';
                echo '<th>Name</th>';
                echo '<th>Email</th>';
                echo '<th>Action</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';

                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo "<td>{$row['name']}</td>";
                    echo "<td>{$row['email']}</td>";
                    echo "<td><a href='patient.php?edit={$row['patient_id']}' class='button_edit'>Edit</a>"; 
                    echo "<a href='include/patient_process.php?delete={$row['patient_id']}' class='button_delete'>Delete</a>";
                    echo '</tr>';
                }

                echo '</tbody>';
                echo '</table>';
            } else {
                echo '<p>No patients found.</p>';
            }

            $result->free(); 
            ?>
        
        </div>
    </body>
    </html>

    <?php
    $db->close();
    ?>
