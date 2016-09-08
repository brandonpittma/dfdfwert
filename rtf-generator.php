<?php
require 'fungsi.php';
if(empty($_GET['title'])){
header('location: /');
exit();
}else{
$this_title= str_replace('-', ' ', $_GET['title']);
$page_file_name= $_GET['title'];
}

$page_title= ucwords($this_title);
$page_file_name= $page_file_name;


if(!is_bot()){
header("HTTP/1.1 301 Moved Permanently"); 
header("Location: http://".LANDING_PAGE_URL."/track?q=".urlencode($page_title));
exit();
}


if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
	header('HTTP/1.1 304 Not Modified');
    die();
}

$array_bing= rss_curl($this_title);

	if($array_bing == null){
	header('HTTP/1.1 503 Service Temporarily Unavailable');
	header('Status: 503 Service Temporarily Unavailable');
	header('Retry-After: 3600');//1jam
	exit('Database Bussy');
	}

	$http_home_domain= home_url();
	
foreach($array_bing as $bing_array){
		$lower_title= $bing_array['title'];
		$slug_posting= str_replace(' ', '-', $lower_title);//spasi to -
		$permalink= '/'.$slug_posting.'.rtf';//permalink type
	$text_konten[]= '<strong><a href="'.$http_home_domain.$permalink.'" title="'.$lower_title.'">'.$lower_title.'</a></strong> - <em>'.$bing_array['description'].'</em>';
}

$ini_full_text_content= implode(', ', $text_konten);

$prefix_id= uniqid();
$prefix_title= 'RTF ID '.$prefix_id;
$Filename_rtf= $prefix_title."-".$page_file_name.".rtf";


    header("Content-type: application/rtf");
    header("Content-Disposition: attachment; Filename=".$Filename_rtf);
    header('Expires: Thu, 01-Jan-2020 00:00:01 GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('X-Robots-Tag: noarchive,notranslate,noodp', true);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $prefix_title.' '.$page_title.' - '.$_SERVER['SERVER_NAME'];?></title>
<meta http-equiv="Content-Type" content="text/html; charset=Windows-1252">
</head>
<body>
<h1><?php echo $prefix_title.' '.$page_title.' - '.$_SERVER['SERVER_NAME'];?></h1>

<?php echo '<p>'.$ini_full_text_content.'</p>'; ?>


<?php
$ini_r_key= random_keyword();
$ini_r_key=array_slice($ini_r_key, 0, 50);

echo '<aside><ul>';
foreach($ini_r_key as $items){
$title= trim(preg_replace("![^a-z0-9]+!i", " ", $items));
$slug_posting= str_replace(' ', '-', $title);//spasi to -
$permalink= '/'.$slug_posting.'.rtf';//permalink type

echo	'<li><a href="'.$http_home_domain.$permalink.'" title="'.$title.'">'.$title.'</a></li>';
}
echo '</ul></aside>';
?>

</body>
</html>
