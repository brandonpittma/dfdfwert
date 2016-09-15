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
		$permalink= '/'.$slug_posting.'.pdf';//permalink type
	$text_konten[]= '<strong><a href="'.$http_home_domain.$permalink.'" title="'.$lower_title.'">'.$lower_title.'</a></strong> - <em>'.$bing_array['description'].'</em>';
}


$ini_full_text_content= implode(', ', $text_konten);

$prefix_id= uniqid();
$Filename_pdf= $prefix_title."-".$page_file_name.".pdf";


header('Expires: Thu, 01-Jan-2020 00:00:01 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('X-Robots-Tag: noarchive,notranslate,noodp', true);

?>
<?php
$ini_r_key= random_keyword();
$ini_r_key=array_slice($ini_r_key, 0, 50);


foreach($ini_r_key as $items){
$title= strtolower(trim(preg_replace("![^a-z0-9]+!i", " ", $items)));
$slug_posting= str_replace(' ', '-', $title);//spasi to -
$permalink= '/'.$slug_posting.'.pdf';

$loploplop []= '<a href="'.$http_home_domain.$permalink.'" title="'.$title.'">'.$title.'</a>';
}
$loploplop = implode(' | ', $loploplop);

?>
<?php
$HTML_STRING= '
<h1><b><i>'.$prefix_title.' '.$page_title.' - '.$_SERVER['SERVER_NAME'].'</i></b></h1>

<p>'.$ini_full_text_content.'</p>
<br><br>
'.$loploplop;


require('writehtmlclass.php');

	$pdf=new PDF_HTML('P', 'mm', 'A3');
    $pdf->SetFont('Arial','',18);
	$pdf->SetTitle($prefix_title.' '.$page_title.' - '.$_SERVER['HTTP_HOST']);
	$pdf->SetSubject($page_title);
	$pdf->SetAuthor($author);
	$pdf->SetCreator($_SERVER['HTTP_HOST']);
	$pdf->SetKeywords(Download Or Read Online .$page_title.' PDF);
	$pdf->AddPage();
	$htmla = $HTML_STRING;
	if(ini_get('magic_quotes_gpc')=='1')
        $htmla=stripslashes($htmla);
    $pdf->WriteHTML($htmla);
	$pdf->Output($Filename_pdf, 'I');
	$pdf->Close();
?>
