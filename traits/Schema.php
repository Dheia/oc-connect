<?php namespace Codecycler\Connect\Traits;

use Codecycler\Connect\Classes\SchemaManager;

trait Schema
{
    public function __construct()
    {
        parent::__construct();

        // Register Schema
        SchemaManager::instance()->register(get_class($this));
    }
}
