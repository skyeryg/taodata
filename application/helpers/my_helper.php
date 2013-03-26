<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');



if ( ! function_exists('curlRequest'))
{
	/**
	 * CURL请求
	 * @param String $url 请求地址
	 * @param Array $data 请求数据
	 */
	function curlRequest($url, $data = false,$referer = '',$cookiefile='')
	{
		$ch = curl_init();
		
		$option = array(
							CURLOPT_URL => $url, 
							CURLOPT_HEADER => 0, 
							CURLOPT_HTTPHEADER => array('Accept-Language: zh-cn','Connection: Keep-Alive','Cache-Control: no-cache'), 
							CURLOPT_USERAGENT => "Dalvik/1.6.0 (Linux; U; Android 4.2; sdk Build/JB_MR1)", 
							CURLOPT_FOLLOWLOCATION => TRUE, 
							CURLOPT_MAXREDIRS => 4, 
							CURLOPT_RETURNTRANSFER => TRUE,
							CURLOPT_REFERER => $referer,
							CURLOPT_COOKIEJAR => $cookiefile,
							CURLOPT_COOKIEFILE => $cookiefile
						);
		
		if ( $data ) {
			$option[CURLOPT_POST] = 1;
			$option[CURLOPT_POSTFIELDS] = $data;
		}
		
		curl_setopt_array($ch, $option);
		$response = curl_exec($ch);
		
		if (curl_errno($ch) > 0) {
			exit("CURL ERROR:$url " . curl_error($ch));
		}
		curl_close($ch);
		return $response;
	}

}


	/** 
 * Send a POST requst using cURL 
 * @param string $url to request 
 * @param array $post values to send 
 * @param array $options for cURL 
 * @return string 
 */ 
function curl_post($url, array $post = NULL, array $options = array()) 
{ 
    $defaults = array( 
        CURLOPT_POST => 1, 
        CURLOPT_HEADER => 0, 
        CURLOPT_URL => $url, 
        CURLOPT_FRESH_CONNECT => 1, 
        CURLOPT_RETURNTRANSFER => 1, 
        CURLOPT_FORBID_REUSE => 1, 
        CURLOPT_TIMEOUT => 4, 
        CURLOPT_POSTFIELDS => http_build_query($post) 
    ); 

    $ch = curl_init(); 
    curl_setopt_array($ch, ($options + $defaults)); 
    if( ! $result = curl_exec($ch)) 
    { 
        trigger_error(curl_error($ch)); 
    } 
    curl_close($ch); 
    return $result; 
} 


function curl_get($url, array $get = NULL, array $options = array()) 
{    
    $defaults = array( 
        CURLOPT_URL => $url. (strpos($url, '?') === FALSE ? '?' : ''). http_build_query($get),
        CURLOPT_HEADER => 0, 
        CURLOPT_RETURNTRANSFER => TRUE, 
        CURLOPT_TIMEOUT => 4 
    ); 
    
    $ch = curl_init(); 
    curl_setopt_array($ch, ($options + $defaults)); 
    if( ! $result = curl_exec($ch)) 
    { 
        trigger_error(curl_error($ch)); 
    } 
    curl_close($ch); 
    return $result; 
} 


function json_to_array($str) {
	if (is_string($str))
		$str = json_decode($str);
	$arr=array();
	foreach($str as $k=>$v) {
		if(is_object($v) || is_array($v))
			$arr[$k]=json_to_array($v);
		else
			$arr[$k]=$v;
	}
	return $arr;
}


function get_mid_str($str,$beg,$end)
{
	$restr =  substr($str,strpos($str,$beg)+strlen($beg));
	$restr = substr($restr,0,strpos($restr,$end));
	return $restr;
}

function del_side_str($str,$beg,$end)
{
	$restr = substr($str,strpos($str,$beg)+strlen($beg));
	$restr = substr($restr,0,strrpos($restr,$end));
	return $restr;
}