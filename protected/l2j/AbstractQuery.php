<?php

abstract class AbstractQuery
{
    /**
     * @var CDbConnection
     */
    protected $_db;

    /**
     * @var Lineage
     */
    protected $_context;

    public function __construct($context)
    {
        $this->_context = $context;
        $this->_db = $context->getDb();
    }
}
