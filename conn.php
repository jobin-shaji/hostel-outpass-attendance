<?php
/*
    usertype 
    3 -> admin
    2 -> security
    1 -> user default
    
    userstatus
    2 -> approved
    1 -> pending

    outpassstatus
    4 -> active
    3 -> canceled
    2 -> declined
    1 -> approved
    0 -> pending

    inmatestatus
    0 -> in 
    1 -> out
*/

require 'vendor/autoload.php';

try {
    $client = new MongoDB\Client("mongodb://localhost:27017");
    $db = $client->hostel;
} catch (Exception $e) {
    die("Connection failed: " . $e->getMessage());
}

// Helper functions for MongoDB operations
function findOne($collection, $filter) {
    global $db;
    return (array)$db->$collection->findOne($filter);
}

function find($collection, $filter = [], $options = []) {
    global $db;
    return $db->$collection->find($filter, $options)->toArray();
}

function insertOne($collection, $document) {
    global $db;
    $result = $db->$collection->insertOne($document);
    return $result;
}

function updateOne($collection, $filter, $update) {
    global $db;
    $result = $db->$collection->updateOne($filter, ['$set' => $update]);
    return $result->getModifiedCount();
}

function deleteOne($collection, $filter) {
    global $db;
    $result = $db->$collection->deleteOne($filter);
    return $result->getDeletedCount();
}
?>
