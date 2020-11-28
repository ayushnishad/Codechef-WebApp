<?php
session_start();
function make_api_request( $path){
    $headers = array(
       "Accept: application/json",
       "Authorization: Bearer ".$_SESSION['access_token']
    );
    return make_curl_request($path, false, $headers);
}
function make_list_api_request($config){
    $category_name=$config['level'];
    $path = $config['api_endpoint']."/problems/".$category_name."?fields=problemName&offset=2&limit=10";
     return make_api_request($path);
   
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
        'level'=>'school'
    );
       // print_r($_SESSION);
    if($_GET['level']){
        $config['level']=$_GET['level'];
        //echo $_GET['level'];
    }
  $response=json_decode(make_list_api_request($config));
   // print_r($response);
    ?>
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" 
    integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" 
    crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
   
</head>
<body>
<div class="container-fluid" style="padding: 0 7% 0 7%;">
    <div class="row">
       <div class="col-12 text-center">
            <p class="h1">CodeChef Problem Search</p>
            <br>
            <form action="" method="GET">
            <label for="cfuid">Choose level</label>
            <select name="level" id="level">
                <option value="school">School</option>
                <option value="easy">Easy</option>
                <option value="medium">Medium</option>
              </select>
            <label for="cfuid">Enter rating</label><input type="number" name="rating">
            <label for="cfuid">Enter tags</label><input type="text" name="tags">
            <button type="submit">Submit</button>
            </form>
        </div>
        <div class="col-12" style="text-align: center;">
            <h4>Level: <?php echo $_GET['level']?></h4>
            <h4>
                No of problems:10
            </h4>
        <table class="table" style="color: white;">
        <thead>
          <tr>
            <th scope="col">Name</th>
            <th scope="col">Link</th>
            
            <!-- <th scope="col">Link</th> -->
            <th scope="col">Difficult level</th>
            <th scope="col">Tags</th>
          </tr>
        </thead>
        <tbody>
        <?php
          foreach($response->result->data->content as $val){
              echo '<tr>';
              print_r('<td>'.$val->problemName.'</td>');
              echo '<td>{{problem.index}}</td>';
              echo '<td>{{problem.rating}}</td>';
              echo '<td>{{problem.tags}}</td>';
              echo '</tr>';
          }
            ?>
    </tbody>
  </table>
    </div>
</div></div>
  
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" 
integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" 
crossorigin="anonymous">
</script>

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" 
integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" 
crossorigin="anonymous">
</script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" 
integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" 
crossorigin="anonymous">
</script>

</body>    
</html>
    <?
}
main()
?>

