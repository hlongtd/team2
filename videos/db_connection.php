<?php

session_start();
$conn = new mysqli('localhost', 'root', '', 'watchnow');

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
