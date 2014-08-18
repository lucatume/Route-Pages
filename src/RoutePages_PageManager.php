<?php

class RoutePages_PageManager
{
    protected $routesMetaOptionName;
    protected $pagesMetaOptionName;
    protected $routesMetaOption;
    protected $pagesMetaOption;
    protected $f;

    const ROUTE_PAGES_META_OPTION_NAME = 'Route_Pages_Meta';

    public function __construct($routesMetaOptionName = null, $pagesMetaOptionName = null, tad_Option $routesMetaOption = null, tad_Option $pagesMetaOption = null, tad_FunctionsAdapterInterface $f = null)
    {
        $this->setRoutesMetaOptionName($routesMetaOptionName);
        $this->setPagesMetaOptionName($pagesMetaOptionName);
        $this->setRoutesMetaOption($routesMetaOption);
        $this->setPagesMetaOption($pagesMetaOption);
        $this->setFunctionsAdapter($f);
    }

    public function getRoutesMetaOptionName()
    {
        return $this->routesMetaOptionName;
    }

    public function setRoutesMetaOptionName($routesMetaOptionName = null)
    {
        $this->routesMetaOptionName = is_string($routesMetaOptionName) ? $routesMetaOptionName : WP_Routing_PersistableRoute::OPTION_ID;
    }

    public function getPagesMetaOptionName()
    {
        return $this->pagesMetaOptionName;
    }

    public function setPagesMetaOptionName($pagesMetaOptionName = null)
    {
        $this->pagesMetaOptionName = $pagesMetaOptionName ? $pagesMetaOptionName : self::ROUTE_PAGES_META_OPTION_NAME;
    }

    public function getRoutesMetaOption()
    {
        return $this->routesMetaOption;
    }

    public function setRoutesMetaOption(tad_Option $routesMetaOption = null)
    {
        $this->routesMetaOption = $routesMetaOption ? $routesMetaOption : tad_Option::on($this->routesMetaOptionName);
    }

    public function createRoutePages()
    {
        $persistedRoutesMeta = $this->routesMetaOption->getValues();
        if(is_array($persistedRoutesMeta)){
            foreach($persistedRoutesMeta as $routeMeta){
                $this->f->wp_insert_post(array(
                    'post_content' => '',
                    'post_name' => $routeMeta['permalink'],
                    'post_title' => $routeMeta['title'],
                    'post_status' => 'publish',
                    'post_type' => $routeMeta['generate']
                ));
            }
        }
    }

    public function getPagesMetaOption()
    {
        return $this->pagesMetaOption;
    }

    public function setPagesMetaOption(tad_Option $pagesMetaOption = null)
    {
        $this->pagesMetaOption = $pagesMetaOption ? $pagesMetaOption : tad_Option::on($this->pagesMetaOptionName);
    }

    public function getFunctionsAdapter()
    {
        return $this->f;
    }

    public function setFunctionsAdapter(tad_FunctionsAdapterInterface $f = null)
    {
        $this->f = $f ? $f : new tad_FunctionsAdapter();
    }
}