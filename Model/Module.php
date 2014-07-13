<?php

class Csvie_Model_Module extends System_Model_Module
{

    protected $module = array(
        'version' => '1.0',
        'module' => 'csvie',
        'title' => 'Csvi',
        'description' => 'import export csv products'
    );
    protected $permissions = array(
        array(
            'controller' => 'admin',
            'action' => 'create',
            'title' => 'Create',
            'description' => 'Create new banner'
        ),
        array(
            'controller' => 'admin',
            'action' => 'delete',
            'title' => 'Delete',
            'description' => 'Delete CSV'
        ),
        array(
            'controller' => 'admin',
            'action' => 'edit',
            'title' => 'Edit',
            'description' => 'Edit csvie'
        ),
        array(
            'controller' => 'admin',
            'action' => 'index',
            'title' => 'Read',
            'description' => 'Read list of csvie'
        ),
    );
    protected $extensionGroup = array(
        'name' => 'Modules',
        'description' => 'All other extensions'
    );

    public function __construct()
    {
        parent::__construct();
        //  $this->sqlFile = dirname(__DIR__) . '/install.sql';
    }

}
