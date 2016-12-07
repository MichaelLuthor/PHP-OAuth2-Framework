<?php
return array (
    'cache' => array(
        'enable' => false,
        'handler' => 'database',
        'default_lifetime' => 24*3600,
        'dbname' => 'dionysos',
        'tablename' => 'dionysos_xview_cache',
    ),
);