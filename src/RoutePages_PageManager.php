<?php

class RoutePages_PageManager
{
    protected $routesMetaOptionName;
    protected $pagesMetaOptionName;
    protected $routesMetaOption;
    protected $pagesMetaOption;
    protected $f;

    private $validPostTypes;

    public function __construct($routesMetaOptionName = null, $pagesMetaOptionName = null, tad_Option $routesMetaOption = null, tad_Option $pagesMetaOption = null, tad_FunctionsAdapterInterface $f = null)
    {
        $this->setRoutesMetaOptionName($routesMetaOptionName);
        $this->setPagesMetaOptionName($pagesMetaOptionName);
        $this->setRoutesMetaOption($routesMetaOption);
        $this->setPagesMetaOption($pagesMetaOption);
        $this->setFunctionsAdapter($f);
        $this->setValidPostTypes($this->f->get_post_types());
    }

    public function setValidPostTypes(array $validPostTypes = null)
    {
        $this->validPostTypes = $validPostTypes ? $validPostTypes : array();
    }

    public function getRoutesMetaOptionName()
    {
        return $this->routesMetaOptionName;
    }

    public function setRoutesMetaOptionName($routesMetaOptionName = null)
    {
        $this->routesMetaOptionName = is_string($routesMetaOptionName) ? $routesMetaOptionName : WPRouting_PersistableRoute::OPTION_ID;
    }

    public function getPagesMetaOptionName()
    {
        return $this->pagesMetaOptionName;
    }

    public function setPagesMetaOptionName($pagesMetaOptionName = null)
    {
        $this->pagesMetaOptionName = $pagesMetaOptionName ? $pagesMetaOptionName : RoutePages::META_OPTION_NAME;
    }

    public function getRoutesMetaOption()
    {
        return $this->routesMetaOption;
    }

    public function setRoutesMetaOption(tad_Option $routesMetaOption = null)
    {
        $this->routesMetaOption = $routesMetaOption ? $routesMetaOption : tad_Option::on($this->routesMetaOptionName);
    }

    /**
     * Conditionally generates route pages based on the value
     * returned by the RoutePages::SHOULD_GENERATE_ROUTE_PAGES
     * filter.
     */
    public function maybeGenerateRoutePages()
    {
        if (!$this->shouldGenerateRoutePages()) {
            return;
        }
        $this->generateRoutePages();
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

    /**
     * @return bool
     */
    protected function shouldGenerateRoutePages()
    {
        /**
         * Developer should hook here to prevent the route page generation
         * from happening.
         * If the filter will not return exactly the `false` boolean then
         * the page generation will happen; easiest way to prevent route
         * page generation from happening is to hook like this
         *
         *     add_filter(RoutePages::SHOULD_GENERATE_ROUTE_PAGES, '__return_false');
         */
        if (!$this->f->apply_filters(RoutePages::SHOULD_GENERATE_ROUTE_PAGES, null)) {
            return false;
        }
        return true;
    }

    /**
     * Will generate route post or cpt for each route that:
     *
     *     * has meta information persisted to the database
     *     * is supposed to generate a cpt of a valid type
     *
     * Usually called via the maybeGenerateRoutePages method
     * to allow for filter hooking.
     */
    public function generateRoutePages()
    {
        $persistedRoutesMeta = $this->routesMetaOption->getValues();
        // get supported post types names
        // will be an empty array if none is found
        if (is_array($persistedRoutesMeta)) {
            foreach ($persistedRoutesMeta as $routeMetaKey => $routeMeta) {
                if (empty($routeMeta['generate']) || !is_string($routeMeta['generate'])) {
                    continue;
                }
                // the route post will be generated only if the
                // generate type is a type the site supports
                $generatePostType = $routeMeta['generate'];
                if (!in_array($generatePostType, $this->validPostTypes)) {
                    continue;
                }
                // the data that will be inserted for the post
                $data = array(
                    'post_content' => '',
                    'post_name' => $routeMeta['permalink'],
                    'post_title' => $routeMeta['title'],
                    'post_status' => 'publish',
                    'post_type' => $generatePostType
                );

                // check if the post has been inserted already
                // will be null if not generated before
                $generatedPostMeta = $this->getGeneratedPostMeta($routeMetaKey);
                $id = null;

                // if the generated post meta is empty then insert a new post
                if (empty($generatedPostMeta)) {
                    $id = $this->f->wp_insert_post($data, false);
                    $routeMeta['ID'] = $id;
                } else {
                    // else updated it if the meta information is different
                    $data['ID'] = $generatedPostMeta['ID'];
                    $routeMeta['ID'] = $generatedPostMeta['ID'];
                    if ($routeMeta != $generatedPostMeta) {
                        $id = $this->f->wp_update_post($data, false);
                    }
                }

                // if the return value of the insertion/update is not an error then store it
                if (!is_a($id, '\WP_Error')) {
                    $this->setGeneratedPostMeta($routeMetaKey, $routeMeta);
                } else {
                    echo $id->get_error_message();
                }
            }
        }
    }

    /**
     * Returns an array containing a route generated post meta.
     *
     * The returned array has the format:
     *     title - the post title
     *     permalink - the post name that will set its permalink relative to the root url
     *     generate - the type of the post the route should genereate
     *     ID - the ID number of the generated post as returned by wp_insert_post or wp_udpate_post functions
     *
     * @param $key The slug for the route meta, it's the camelCase version of the permalink
     * @return mixed Either an array in the format above or null
     */
    public function getGeneratedPostMeta($key){
        if (!is_string($key)) {
            throw new BadMethodCallException('Key must be a string', 1);
        }
        return $this->pagesMetaOption->$key;
    }

    /**
     * Sets the meta for a route generated post.
     *
     * The array to insert has the format:
     *     title - the post title
     *     permalink - the post name that will set its permalink relative to the root url
     *     generate - the type of the post the route should genereate
     *     ID - the ID number of the generated post as returned by wp_insert_post or wp_udpate_post functions
     *
     * @param $key The slug for the route meta, it's the camelCase version of the permalink
     * @param $value An array in the format above or null
     */
    public function setGeneratedPostMeta($key, $value){
        if (!is_string($key)) {
            throw new BadMethodCallException('Key must be a string', 1);
        }
        $this->pagesMetaOption->$key = $value;
    }
}