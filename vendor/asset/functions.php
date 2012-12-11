<?php

function output($text, $nonl2br = false, $nobbcode = false)
{
	$text = str_replace('"',"CAAAAC",$text);
	$text = str_replace(':',"2DOTS",$text);

	if ($nobbcode) {
		return preg_replace('/\[(\/|)\S+\]/', '', $text);
	}

	if ($nonl2br) {
		$text = $text;
	}
	else {
		$text = nl2br($text);
	}

	$text = str_replace("[hr]",'<HR />',$text);

	//Arrays for the bbCode replacements
    $bbcode_regex = array(
		0 => '/\[color\=(.+?)\](.+?)\[\/color\]/s',
		1 => '/\((.*?)\)/s',
		2 => '/\[(.*?)\]/s',

	);

    $bbcode_replace = array(
		0 => '<span style="color:$1">$2</span>',
		1 => '<span style="color:black">(<span style="color:red;">$1</span>)</span>',
		2 => '<span style="color:black">[<span style="color:blue;"><b>$1</b></span>]</span>',
	);

	ksort($bbcode_regex);
    ksort($bbcode_replace);

    //preg_replace to convert all remaining bbCode tags

    $post_bbcode_treated = preg_replace($bbcode_regex, $bbcode_replace, $text);
	$post_bbcode_treated = str_replace("CAAAAC",'"',$post_bbcode_treated);
	$post_bbcode_treated = str_replace("2DOTS",':',$post_bbcode_treated);
	$post_bbcode_treated = str_replace("\\",'',$post_bbcode_treated);

	return $post_bbcode_treated;
}


function v($varName, $method = false, $default = false,$numeric = false)
{
	if ($method == 'POST')
		$value = ( isset($_POST[$varName]) ) ? $_POST[$varName] : ( ($default) ? $default : '' );
	elseif ($method == 'GET')
		$value = ( isset($_GET[$varName]) ) ? $_GET[$varName] : ( ($default) ? $default : '' );
	else
		$value = ( isset($_REQUEST[$varName]) ) ? $_REQUEST[$varName] : ( ($default) ? $default : '' );

	return ($numeric) ? intval($value) : $value;
}

// -- AJAX Perpage Fucntion...

function ajax_perpage($id,$num_items, $per_page, $start_item)
{
	$lang['Page_of'] = "صفحة %d من %d";
	$total_pages = ceil($num_items/$per_page);

	if ($total_pages <= 1)
		return '';

	$on_page = floor($start_item / $per_page) + 1;
	$pages = sprintf($lang['Page_of'], $on_page, $total_pages);
	$data = '<SELECT onchange="if (this.value != \'\') location.href=(this.value);">';
	for($i = 1; $i < $total_pages + 1; $i++) {
		$link = ( ( $i - 1 ) * $per_page );
		$data .= ( $i == $on_page ) ? '<option Value=\'Javascript:Sora_Update('.$id.','.$link.');\' selected>صفحه '.$i.' من '.$total_pages.'</option>' : '<option Value=\'Javascript:Sora_Update('.$id.','.$link.');\'>صفحه '.$i.' من '.$total_pages.'</option>';
	}
	$data .= "</select>";
	return $data;
}

function o_z($str) {
	$str = htmlspecialchars($str);
	$str = preg_replace('#\r?\n#', '<BR />', $str);
	return $str;
}

#EOF;