<?php
return array(
'storage_handler' => 'Pdo', 
'storage_params'=>array('dsn'=>'mysql:dbname=my_oauth2_db;host=localhost', 'username'=>'root', 'password'=>'oyys'),
'enable_grant_type_client_credentials' => true,
'enable_grant_type_authorization_code' => true,
);