<?php
ob_start();

require ('./libs/application.php');
require ('./libs/pdo.php');

class MyDB extends SQLite3
{
  function __construct()
  {
    $this->open('database/amazon.db');
  }
}

$db = new MyDB();

// now save it
$name = $_GET['name'];
$avg_rank = $_GET['avg_rank'];
$total_results = $_GET['results'];
$avg_price = $_GET['avg_price'];

// need to make sure it doesn't exist since name is pk

$db->exec("INSERT INTO saved_search (name, avg_rank, competition, avg_price) 
VALUES ('$name', $avg_rank, $total_results, $avg_price)");

// clear out the output buffer
while (ob_get_status()) 
{
    ob_end_clean();
}

// no redirect
header( "Location: http://amazon.mayuli.com/get.php?alert=saved" );