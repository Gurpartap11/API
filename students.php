<?php

require_once 'connection.php';

// Access action information from the $_SERVER method
$method = $_SERVER['REQUEST_METHOD'];

// GET route for retrieving all student records
if ($method == 'GET') {
    $sql = "SELECT * FROM student";
    $result = mysqli_query($conn, $sql);
    $students = array();
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($students, $row);
    }
    echo json_encode($students);
}

// POST route for creating a new student record
if ($method == 'POST') {
    $request_body = file_get_contents('php://input');
    $data = json_decode($request_body, true);
    $id = $data['id'];
    $student_name = $data['student_name'];
    $student_number = $data['student_number'];
    $student_age = $data['student_age'];

    $sql = "INSERT INTO student (id, student_name, student_number, student_age) VALUES ('$id', '$student_name', '$student_number', '$student_age')";
    if (mysqli_query($conn, $sql)) {
        echo "New student record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

// PUT route for updating a student record
if ($method == 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    update_student($data['id'], $data['student_name'], $data['student_number'], $data['student_age']);

}

// DELETE route for deleting a student record
if ($method == 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
    delete_student($data['id']);
}

function update_student($id, $student_name, $student_number, $student_age)
{
    global $conn;
    $sql = "UPDATE student SET student_name = '$student_name', student_number = '$student_number', student_age = '$student_age' WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        echo "Student record updated successfully";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

function delete_student($id)
{
    global $conn;
    $sql = "DELETE FROM student WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        echo "Student record deleted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

mysqli_close($conn);

?>