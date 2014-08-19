<?php

class tad_HeadwayGlobalSettingsWrapper extends tad_Statictadtad_Staticwrapperstad_StaticOption
{
    const OPTION_NAME = 'headway_option_group_general';
    
    protected $prefix;
    
    public function __construct($prefix = '', tad_FunctionsAdapterInterface $functions = null)
    {
        if (!is_string($prefix)) {
            throw new tad_StaticBadMethodCallException("Prefix must be a string", 1);
        }
        $this->prefix = $prefix;
        parent::__construct($functions);
        $this->optionSlug(self::OPTION_NAME)->load();
    }
    public static function on($prefix = '', tad_FunctionsAdapterInterface $functions = null)
    {
        return new self($prefix, $functions);
    }
    protected function loadPropertiesFrom($value)
    {
        
        // filter the variables using the prefix
        $filtered = array();
        if (!$this->prefix) {
            $filtered = $value;
        } else {
            foreach ($value as $key => $val) {
                if (strpos($key, $this->prefix) === 0) {
                    $truncatedKey = str_replace($this->prefix, '', $key);
                    $filtered[$truncatedKey] = $val;
                }
            }
        }
        if ($filtered == array()) {
            $this->val = array();
        } else {
            
            // load the properties
            parent::loadPropertiesFrom($filtered);
        }
    }
    public function __destruct(){
        // nothing, to prevent trying to update the option
    }
}
