<?php

include 'db_connection.php';


if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];

    $stmt = $db->prepare("INSERT INTO patients (name, email) VALUES (?, ?)");

    if ($stmt) {
        $stmt->bind_param("ss", $name, $email);
        $stmt->execute();
        $stmt->close();
        echo "Patient added successfully!";
    } else {
       
        echo "Error preparing statement: " . $db->error;
    }
}


if (isset($_POST['edit'])) {
    $patient_id = $_POST['patient_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];

    $stmt = $db->prepare("UPDATE patients SET name=?, email=? WHERE patient_id=?");

    if ($stmt) {
        $stmt->bind_param("ssi", $name, $email, $patient_id);
        $stmt->execute();
        $stmt->close();
        echo "Patient edited successfully!";
    } else {
        
        echo "Error preparing statement: " . $db->error;
    }
}


if (isset($_GET['delete'])) {
    $patient_id = $_GET['delete'];

    
    $stmt = $db->prepare("DELETE FROM patients WHERE patient_id=?");

    if ($stmt) {
        $stmt->bind_param("i", $patient_id);
        $stmt->execute();
        $stmt->close();
        echo "Patient deleted successfully!";
    } else {
        
        echo "Error preparing statement: " . $db->error;
    }
}


header("Location: ../patient.php");
exit();
?>
