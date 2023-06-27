<?php
include 'Database.php';

$db = new Database(
    'localhost',
    'new',
    'root',
    ''
);
// fetch example
$menuList = $db->findAll('db_menu');

echo '<pre>';
print_r($menuList);


// insert example
//$insert = $db->insert('db_menu', [
//   'menu_name' => 'deneme2'
//]);
//
//echo !$insert ? "success" : "error";


//$update = $db->update('db_menu', 'menu_id', '1', [
//    'menu_name' => 'id1 example'
//]);
//echo !$update ? "success" : "error";
