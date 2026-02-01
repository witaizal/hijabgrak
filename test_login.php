<?php
ob_start();
header('Content-Type: application/json');
echo json_encode(['status' => 'success', 'message' => 'Test JSON']);
ob_end_flush();
?>