<?php
return array(
'api' => array(
    'title' => 'API',
    'menu' => array(
        'api' => array(
            'title' => 'API',
            'subMenu' => array(
                'management' => array('title'=>'Management'),
                'new' => array('title'=>'New API'),
            ),
        ),
        'test' => array(
            'title' => 'Test',
            'subMenu' => array(
                'authorization' => array('title'=>'Authorization'),
                'api' => array('title'=>'API'),
            ),
        ),
        'document' => array(
            'title' => 'Document',
            'subMenu' => array(
                'online' => array('title'=>'Online'),
                'export' => array('title'=>'Export'),
            ),
        ),
        'sdk' => array(
            'title' => 'SDK',
            'subMenu' => array(
                'php' => array('title'=>'PHP'),
                'javascript' => array('title'=>'JavaScript'),
                'java' => array('title'=>'Java'),
                'python' => array('title'=>'Python'),
            ),
        ),
    ),
),
'configuration' => array(
    'title' => 'Configuration',
    'menu' => array(
        'clients' => array(
            'title' => 'Clients',
            'subMenu' => array(
                'management' => array('title'=>'Management'),
                'new' => array('title'=>'New Client'),
            ),
        ),
        'oauth' => array(
            'title' => 'OAuth',
        ),
    ),
),
);