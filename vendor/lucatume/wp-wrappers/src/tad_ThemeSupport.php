<?php

class tad_ThemeSupport
{
    protected $functions;
    protected $toAdd;
    protected $toRemove;
    
    public function __construct(tad_FunctionsAdapterInterface $functions = null)
    {
        if (is_null($functions)) {
            $functions = new tad_FunctionsAdapter();
        }
        $this->functions = $functions;
        $this->toAdd = array();
        $this->toRemove = array();
        $this->functions->add_action('after_setup_theme', array($this, 'addAndRemove'));
    }
    public static function addSupport($themeSupport, $arguments = null)
    {
        $instance = new self();
        $instance->add($themeSupport, $arguments);
    }
    public static function removeSupport($themeSupport, $arguments = null)
    {
        $instance = new self();
        $instance->remove($themeSupport, $arguments);
    }
    protected function addOrRemove($themeSupport, $arguments = null, $merge = true)
    {
        if (!is_string($themeSupport)) {
            throw new \BadMethodCallException("Theme support must be a string", 1);
        }
        if (!is_null($arguments) and !is_array($arguments)) {
            throw new \BadMethodCallException("Arguments must be an array", 2);
        }
        $target = 'toRemove';
        $opposite = 'toAdd';
        if ($merge) {
            $target = 'toAdd';
            $opposite = 'toRemove';
        }
        $this->$target = array_merge_recursive($this->$target, array($themeSupport => $arguments));
        $this->$opposite = array_diff_key($this->$opposite, $this->$target);
    }
    public function add($themeSupport, $arguments = null)
    {
        if (is_string($arguments)) {
            $arguments = array($arguments);
        }
        $this->addOrRemove($themeSupport, $arguments, true);
    }
    public function remove($themeSupport, $arguments = null)
    {
        if (is_string($arguments)) {
            $arguments = array($arguments);
        }
        $this->addOrRemove($themeSupport, $arguments, false);
    }
    public function addAndRemove()
    {
        foreach (array_keys($this->toAdd) as $feature) {
            $params = array($feature);
            if (is_array($this->toAdd[$feature])) {
                $params = array_merge($params, $this->toAdd[$feature]);
            }
            call_user_func_array(array($this->functions, 'add_theme_support'), $params);
        }
        foreach (array_keys($this->toRemove) as $feature) {
            $this->functions->remove_theme_support($feature);
        }
    }
    
    public function getToAdd()
    {
        return $this->toAdd;
    }
    
    public function getToRemove()
    {
        return $this->toRemove;
    }
    public function getFunctions()
    {
        return $this->functions;
    }
    public function __get($property)
    {
        $feature = tad_Str::hyphen($property);
        if (isset($this->toAdd[$feature])) {
            return $this->toAdd[$feature];
        }
        return null;
    }
}
