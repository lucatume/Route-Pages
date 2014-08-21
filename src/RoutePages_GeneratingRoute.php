<?php

class RoutePages_GeneratingRoute  extends  WPRouting_PersistableRoute
{
    protected $generatedPostType = false;

    /**
     * Makes the generating route hook into the 'WP_Routing_PersistableRoute_persist_route' filter hook.
     *
     * @return void|RoutePages_GeneratingRoute
     */
    public function hook(){
        parent::hook();
        $this->f->add_filter(WPRouting_PersistableRoute::ROUTE_PERSISTED_VALUES_FILTER, array($this, 'addRouteMetaArgs'));
        return $this;
    }

    /**
     * Checks if the route is one generating a post entry.
     *
     * @param null/string $postType Use the parameter to narrow down the check.
     * @return bool
     */
    public function isGenerating($postType = null){
        $postType = $postType ? $postType == $this->generatedPostType : true;
        return (bool)($this->generatedPostType && $postType );
    }

    /**
     * Sets the route to be one generating a post type entry.
     *
     * Please note that by default the route will not generate a post entry.
     *
     * @param string $postType The post type the route should generate, defaults to 'page'.
     * @return RoutePages_GeneratingRoute $this The calling instance itself.
     */
    public function shouldGenerate($postType = 'page'){
        if(!is_string($postType)){
            throw new BadMethodCallException('Post type must be a string', 1);
        }
        $this->generatedPostType = $postType;
        return $this;
    }

    /**
     * Adds the 'generate' route meta information to the route meta information.
     *
     * Should be called by the 'WP_Routing_PersistableRoute_persist_route' filter hook.
     *
     * @param array $routeArgs
     * @param $routeId
     * @return array
     */
    public function addRouteMetaArgs(array $routeArgs, $routeId){
        if(!is_string($routeId)){
            throw new BadMethodCallException('Route Id must be a string', 1);
        }
        $routeArgs['generate'] = $this->generatedPostType;
        return $routeArgs;
    }
    public function setGeneratedPostType($postType){
        $this->generatedPostType = is_string($postType) ? $postType : 'page';
        return $this;
    }
    public function getGeneratedPostType(){
        return $this->generatedPostType;
    }
} 