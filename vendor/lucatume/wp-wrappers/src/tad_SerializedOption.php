<?php
class tad_SerializedOption
{
    protected $f;
    protected $option;
    protected $isSiteOption;
    protected $val;
    protected $_data;

    // public function __c
    // {
    //     if (!is_string($optionName)) {
    //         throw new \InvalidArgumentException("tad_OptionWrapper name must be a string", 1);
    //     }
    //     if (!is_bool($isSiteOption)) {
    //         throw new \InvalidArgumentException("Is site option must be a boolean value", 2);
    //     }
    //     if (is_null($f)) {
    //         $f = new tad_FunctionsAdapter();
    //     }
    //     $this->functions = $f;
    //     $suffix = '';
    //     if ($isSiteOption) {
    //         $suffix = '_site';
    //     }
    //     $funcName = sprintf('get%s_option', $suffix);
    //     // defaults to an empty array
    //     $this->val = $this->functions->$funcName($optionName, array());
    //     $buf = @unserialize($this->val);
    //     if (false !== $buf or $buf === 'b:0') {
    //         $this->val = $buf;
    //     }
    //     $this->loadPropertiesFrom($this->val);
    // }
    public function __construct(tad_FunctionsAdapterInterface $f = null){
        if (!$f) {
            $f = new tad_FunctionsAdapter();
        }
        $this->f = $f;
    }
    public function __set($key, $value){
        if ($key == 'option') {
            if (!is_string($value)) {
                throw new \BadMethodCallException("tad_OptionWrapper name should be a string", 1);
            }
            $this->option = $value;
        }
    }
    public static function on($optionName, $isSiteOption = false, tad_FunctionsAdapterInterface $f = null)
    {
        // $instance = new self($optionName, $isSiteOption, $f); 
        $instance = new self($f); 
        $instance->option($optionName);
        $instance->isSiteOption($isSiteOption);
        return $instance;
    }
    protected function loadPropertiesFrom($arr)
    {
        $this->_data = tad_Arr::camelBackKeys($this->val);
    }
    public function __get($key)
    {
        if ($key == 'val') {
            return $this->val;
        }
        if (!tad_Arr::isAssoc($this->_data)) {
            return null;
        }
        if (array_key_exists($key, $this->_data)) {
            return $this->_data[$key];
        }
        return null;
    }

    public function option($value = null)
    {
        if (!$value){
            return $this->option;
        }
        else {
            if (!is_string($value)) {
                throw new \BadMethodCallException("tad_OptionWrapper name must be a string", 1);
            }
            $this->option = $value;
        }
    }

    public function isSiteOption($value = null)
    {
        if (is_null($value)) {
            return $this->isSiteOption;
        } else {
            if (!is_bool($value)) {
                throw new \BadMethodCallException("isSiteOption must be a boolean value", 1);
            }
            $this->isSiteOption = $value;
        }
    }
}
