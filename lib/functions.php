<?php


add_action('update_option_share-juice-options', 'sj_empty_cache');
add_action('update_option_share-juice-options', 'sj_show_options_updated');

function sj_empty_cache()
{
    // Clear all W3 Total Cache

    if (class_exists('W3_Plugin_TotalCacheAdmin')) {
        $plugin_totalcacheadmin = &w3_instance('W3_Plugin_TotalCacheAdmin');

        $plugin_totalcacheadmin->flush_all();

        echo __('<div class="updated"><p>All <strong>W3 Total Cache</strong> caches successfully emptied.</p></div>');
    }


}
function sj_show_options_updated()
{
    echo '<div class="updated"><p>Options updated</p></div>';
}


function get_short_url($url)
	
{
	
	
	
	global $option;
	
	
	
	if($option['admin_options']['url_sht_service'] == 'bitly')
		
	$short_url = get_bitly_url($url);
	
	
	
	if($option['admin_options']['url_sht_service'] == 'isgd')
		
	$short_url = get_isgd_url($url);
	
	
	
	if($option['admin_options']['url_sht_service'] == 'tinyurl')
		
	$short_url = get_tinyurl_url($url);
	
	
	
	return $short_url;
	
}





function get_isgd_url($url)
	
{
	
	
	
	$ch = curl_init();
	
	curl_setopt($ch, CURLOPT_URL, "http://is.gd/create.php?format=simple&url={$url}");
	
	curl_setopt($ch, CURLOPT_HEADER, 0);
	
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
	
	
	$response = curl_exec($ch);
	
	curl_close($ch);
	
	
	
	return $response;
	
}





function get_tinyurl_url($url)
	
{
	
	
	
	$ch = curl_init();
	
	curl_setopt($ch, CURLOPT_URL, "http://tinyurl.com/api-create.php?url={$url}");
	
	curl_setopt($ch, CURLOPT_HEADER, 0);
	
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
	
	
	$response = curl_exec($ch);
	
	curl_close($ch);
	
	
	
	return $response;
	
}





function get_bitly_url($url)
	
{
	
	
	
	global $option;
	
	
	
	$login = $option['admin_options']['bitly_login'];
	
	$apikey = $option['admin_options']['bitly_apikey'];
	
	
	
	$query = array(
	
	"version" => "3",
	
	"longUrl" => $url,
	
	"login" => $login, 
	
	"apiKey" => $apikey 
	
	);
	
	$query = http_build_query($query);
	
	
	
	$ch = curl_init();
	
	curl_setopt($ch, CURLOPT_URL, "http://api.bit.ly/shorten?".$query);
	
	curl_setopt($ch, CURLOPT_HEADER, 0);
	
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
	$response = curl_exec($ch);
	
	curl_close($ch);
	
	$response = json_decode($response);
	
	
	
	if($response->errorCode == 0 && $response->statusCode == "OK") {
		
		return $response->results->{$url}->shortUrl;
		
		} else {
		
		return null;
		
	}
	
	
	
}

?>