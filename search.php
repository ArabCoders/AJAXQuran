<?php
define('ROOT', __DIR__);
require_once(ROOT.'/vendor/asset/bootstrap.php');

$q = v('q', '');

if (!$q)
	die("لم تقم بكتابة كلمة البحث");

$encoding 	= mb_detect_encoding($q, 'auto');
$q			= ($encoding == 'UTF-8') ? $q : mb_convert_encoding($q, 'UTF-8', $encoding);

$arr = array();

$soraID = ( v('soraID') ) ? "AND s.sora_id = ".v('soraID', '', false, true)." AND s.sora_id = a.sora_id" : 'AND s.sora_id = a.sora_id';

$sql = "SELECT s.sora_id, s.sora_title, a.aya_explain, a.aya_text, a.aya_text2, a.aya_id FROM
	ac_aya a, ac_sora s WHERE ac_aya MATCH '".$db->escapeString($q)."' {$soraID} ORDER by a.sora_id ASC";

$result = $db->query($sql);

$totalrows = 0;

$words = explode(" ", $q);

while ($row = $result->fetchArray())
{
	$totalrows++;

	for ($x=0,$total_keywords = count($words); $x<$total_keywords; $x++)
	{
		$row['aya_text2'] 	= str_replace($words[$x],'[color=red]'.$words[$x].'[/color]', $row['aya_text2']);
		$row['aya_explain'] = str_replace($words[$x],'[color=red]'.$words[$x].'[/color]', $row['aya_explain']);
	}
	$arr['ITEMS'][] = array(
 		'ID' 		=> $totalrows+1,
   		'AYA_ID'	=> $row['aya_id'],
   		'EXPLAIN' 	=> output($row['aya_explain']),
		'TEXT' 		=> output($row['aya_text2']),
		'TEXT2'	 	=> htmlspecialchars($row['aya_text']),
		'SORA_ID' 	=> $row['sora_id'],
		'SORA_NAME' => $row['sora_title'],
	);
}

if (!$totalrows)
	die("لا يوجد أي نتايج لماتبحث --> ".$q);

$arr += array(
	'TOTALROWS' => str_replace(',','،',number_format($totalrows)),
);

$twig->display('search.twig', $arr);

#EOF;