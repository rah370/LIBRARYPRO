<?php
// Minimal contact endpoint. Stores submissions to frontend/storage/contacts.json
if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    http_response_code(405);
    echo json_encode(['error'=>'Method not allowed']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
if(!$data){
    // fallback to form-encoded
    $data = $_POST;
}
$name = trim($data['name'] ?? '');
$email = trim($data['email'] ?? '');
$message = trim($data['message'] ?? '');
if(!$name || !filter_var($email, FILTER_VALIDATE_EMAIL) || !$message){
    http_response_code(422);
    echo json_encode(['error'=>'Invalid input']);
    exit;
}
$storeDir = __DIR__ . '/storage';
if(!is_dir($storeDir)) mkdir($storeDir, 0755, true);
$file = $storeDir . '/contacts.json';
$entry = [
    'name'=>$name,
    'email'=>$email,
    'message'=>$message,
    'ts'=>time(),
    'ip'=>$_SERVER['REMOTE_ADDR'] ?? ''
];
$all = [];
if(file_exists($file)){
    $json = json_decode(file_get_contents($file), true);
    if(is_array($json)) $all = $json;
}
$all[] = $entry;
file_put_contents($file, json_encode($all, JSON_PRETTY_PRINT));
header('Content-Type: application/json');
echo json_encode(['ok'=>true]);
