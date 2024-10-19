<?php
require_once '../config.php';

$id = $_GET['id'];
$status = $_GET['status'];

$conn = mysqli_connect("localhost", "root", "", "photogallery");

if ($conn === false) {
  die(json_encode(['success' => false, 'message' => 'Error: Could not connect' . mysqli_connect_error()]));
}

$sql = "UPDATE albums SET status = '$status' WHERE id = $id";

if ($conn->query($sql) === TRUE) {
  echo json_encode(['success' => true]);
} else {
  echo json_encode(['success' => false, 'message' => 'Error updating record: ' . $conn->error]);
}

$conn->close();
?>