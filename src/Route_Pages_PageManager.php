<?php

class Route_Pages_PageManager
{
    protected $routesMetaOptionName;

    public function __construct($routesMetaOptionName = null)
    {
        $this->setRoutesMetaOptionName($routesMetaOptionName);
    }

    public function getRoutesMetaOptionName()
    {
        return $this->routesMetaOptionName;
    }

    public function setRoutesMetaOptionName($routesMetaOptionName = null)
    {
        $this->routesMetaOptionName = is_string($routesMetaOptionName) ? $routesMetaOptionName : WP_Routing_PersistableRoute::OPTION_ID;
    }
} 