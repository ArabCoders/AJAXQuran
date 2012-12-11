<?php

define('ROOT', __DIR__);
require_once(ROOT.'/vendor/asset/bootstrap.php');

$arr = array();

$id			= v('id', '', 1, true);
$start 		= v('start', '', 0, true);
$perpage 	= v('perpage', '', 15, true);

// -- Get sora Information...
$sql = "SELECT sora_id as ID, sora_title as SORA FROM ac_sora";
$result = $db->query($sql);
while($row = $result->fetchArray(SQLITE3_ASSOC) )
{
	if ($id == $row['ID'])
		$row['SELECTED'] = 1;

	$arr['SORA'][] = $row;
}

$sql 	= "SELECT COUNT(aya_id) as TOTAL FROM ac_aya WHERE sora_id = {$id}";
$result = $db->query($sql);
$total	= $result->fetchArray(SQLITE3_ASSOC);
$total	= $total['TOTAL'];

$sql = "SELECT * FROM ac_aya WHERE sora_id = :id ORDER BY aya_id ASC LIMIT :start,:perpage";
$stmt = $db->prepare($sql);
$stmt->bindValue(':id', $id, SQLITE3_INTEGER);
$stmt->bindValue(':start', $start, SQLITE3_INTEGER);
$stmt->bindValue(':perpage', $perpage, SQLITE3_INTEGER);
$result = $stmt->execute();

while($row = $result->fetchArray(SQLITE3_ASSOC) )
{
	$arr['AYA'][] = array(
		'ID' 		=> $row['aya_id'],
		'TEXT' 		=> output($row['aya_text']),
		'TEXT2' 	=> o_z(output($row['aya_text'])),
		'EXPLAIN' 	=> o_z(output($row['aya_explain']))
	);
}

$arr += array(
	'PERPAGE' => ajax_perpage($id, $total, $perpage, $start),
);
$request_type = ( isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ) ? 1 : 0;

$twig->display( ( (!$request_type) ? 'index.twig' : 'ajax.twig' ),$arr);
