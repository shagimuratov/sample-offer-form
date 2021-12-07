<!doctype html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<style>
  body {
    padding: 1em;
  }
  @media (max-width: 980px) {
    body {
      padding-top: 0;
    }
  }
</style>
 
    <title>A welcome page</title>
</head>
<body padding=1 margin=1>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<?php

$form_submited = false;
if (isset($_POST) && count($_POST)) {
    $gr_result = false; 
    $form_submited = true;
    $spam = false;

    var_dump($_POST);
    $url = 'https://www.google.com/recaptcha/api/siteverify'; 

    //The data you want to send via POST
    $fields = [
        'secret'         => '',
        'response'         => isset($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : null,
        'remoteip'         => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null,
    ];

    //url-ify the data for the POST
    $fields_string = http_build_query($fields);

    //open connection
    $ch = curl_init();

    //set the url, number of POST vars, POST data
    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_POST, true);
    curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

    //So that curl_exec returns the contents of the cURL; rather than echoing it
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 

    //execute post
    $result = curl_exec($ch);
    if ($result) {
        
        $gr_result = json_decode($result, true);
    }
    
    //
    // We got positive results from reCaptcha.
    //
    if (isset($gr_result['score']) && $gr_result['score'] > 0.5) {
        $spam = false; 
    }
}

?>
<?php
if ($form_submited === true) {
?> 
    <script src="https://www.google.com/recaptcha/api.js"></script>
    <script>
       function onSubmit(token) {
         document.getElementById("quote-form").submit();
       }
    </script>
    <h1>You are welcome!</h1>
    <h2>A quote request</h2>
 <?php
}
 ?>   
    <form method=post id="quote-form">
        <div class="form-group row">
            <label for="email" class="col-sm-2 col-form-label">Email:</label>
            <div class="col-sm-10">
                <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="Enter email">
                <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
            </div>
        </div>

        <div class="form-group row">
            <label for="message" class="col-sm-2 col-form-label">Message:</label>
            <div class="col-sm-10">
            <textarea name="message" id="message" class="form-control" ></textarea>
            </div>
        </div>
        <br /> 
        <div class="form-group">
        <button class="g-recaptcha btn btn-primary" 
                data-sitekey="6LfCtX4dAAAAAJs5eVf60uehwGtRZCsEVTjM9kzo" 
                data-callback='onSubmit' 
                data-action='submit'>Submit</button>
        </div>
    </form>
</body>
</html>


