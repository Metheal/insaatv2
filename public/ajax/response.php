<?php
function respondWithError($message)
{
    echo json_encode(['success' => false, 'message' => $message]);
    exit;
}

function respondWithSuccess($message, $data = [])
{
    echo json_encode(array_merge(['success' => true, 'message' => $message], $data));
    exit;
}
