<?php
session_start();
function take_user_to_codechef_permissions_page($config){

    $params = array('response_type'=>'code', 'client_id'=> $config['client_id'], 'redirect_uri'=> $config['redirect_uri'], 'state'=> 'xyz');
    header('Location: ' . $config['authorization_code_endpoint'] . '?' . http_build_query($params));
    die();
}
function generate_access_token_first_time($config, $oauth_details){

    $data= array('grant_type' => 'authorization_code', 'code'=> $oauth_details['authorization_code'], 'client_id' => $config['client_id'],
                          'client_secret' => $config['client_secret'], 'redirect_uri'=> $config['redirect_uri']);                
     
    $response=json_decode(make_curl_request($config['access_token_endpoint'], $data));
    $result = $response->result->data;
    $oauth_details['access_token'] = $result->access_token;
    $oauth_details['refresh_token'] = $result->refresh_token;
    $oauth_details['scope'] = $result->scope;
   return $oauth_details;

}
function make_curl_request($url,$data=false,$headers = array())
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    if($data){
    $headers = array(
        'Accept:application/json',
    'content-Type: application/json',
    );    
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); 
    }          
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//for debug only!
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $resp = curl_exec($ch);
    curl_close($ch);
    return $resp;
   
}
function main(){

    $config = array('client_id'=> 'ecad34d16a44a843bc67b6ab96f7d1bb',
        'client_secret' => '6a9bbdeb9dab46736553f11dff300109',
        'api_endpoint'=> 'https://api.codechef.com/',
        'authorization_code_endpoint'=> 'https://api.codechef.com/oauth/authorize',
        'access_token_endpoint'=> 'https://api.codechef.com/oauth/token',
        'redirect_uri'=> 'http://localhost:8000/gdchef.php',
        'website_base_url' => 'http://localhost:8000/gdchef.php');

    $oauth_details = array('authorization_code' => '',
        'access_token' => '',
        'refresh_token' => '');
    
        if(isset($_GET['code'])){
            $oauth_details['authorization_code']
             = $_GET['code'];
            //echo $_GET['code'];
          $oauth_details= generate_access_token_first_time($config, $oauth_details);
         $_SESSION['access_token']=$oauth_details['access_token'];
         header('Location: requestmaker.php');
        } else{
            take_user_to_codechef_permissions_page($config);
        }

}

main();
?>
