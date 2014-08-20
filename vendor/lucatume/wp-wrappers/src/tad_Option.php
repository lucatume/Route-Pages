<?php

class tad_Option
{
    protected $slug = '';
    protected $isSiteOption = false;
    protected $f;
    protected $loadedValue;
    protected $originalKeys;
    protected $val;
    protected $suffix = '';
    protected $underscoreProperties = false;
    
    public function __construct(tad_FunctionsAdapterInterface $f = null)
    {
        if (is_null($f)) {
            $f = new tad_FunctionsAdapter();
        }
        $this->f = $f;
    }
    
    public static function on($slug, tad_FunctionsAdapterInterface $f = null)
    {
        $instance = new self($f);
        $instance->slug($slug);
        $instance->load();
        return $instance;
    }
    
    public function slug($value = null)
    {
        if (!is_null($value)) {
            if (!is_string($value)) {
                throw new BadMethodCallException("tad_Option slug must be a string", 1);
            }
            $this->slug = $value;
            return $this;
        } else {
            return $this->slug;
        }
    }
    
    public function load()
    {
        if ($this->isSiteOption) {
            $this->suffix = '_site';
        }
        $functionName = sprintf('get%s_option', $this->suffix);
        
        // get the option value; default to empty array
        $value = $this->f->$functionName($this->slug);
        $value = $value ? $value : array();
        // load values from the option
        $this->loadPropertiesFrom($value);
        
        // return this to allow chaining
        return $this;
    }
    
    protected function loadPropertiesFrom($value)
    {
        
        // if it's not an associative array then return
        if (!tad_Arr::isAssoc($value)) {
            return;
        }
        
        // store the original keys and save their
        // camelBack counterpart
        $this->originalKeys = array();
        
        // reset the val array
        $this->val = array();
        $originalKeys = array_keys($value);
        foreach ($originalKeys as $key) {
            
            // which function to use for key naming?
            $funcName = 'camelBack';
            if ($this->underscoreProperties) {
                $funcName = 'underscore';
            }
            
            // original ['some key' => 'value']
            $newKey = tad_Str::$funcName($key);
            
            // ['someKey' => 'some key']
            $this->originalKeys[$newKey] = $key;
            
            // ['someKey' => 'value']
            $this->val[$newKey] = $value[$key];
        }
    }
    
    public function __destruct()
    {
        $functionName = sprintf('update%s_option', $this->suffix);
        
        // for array options restore the original keys
        if (tad_Arr::isAssoc($this->val)) {
            $buffer = array();
            foreach ($this->val as $key => $value) {
                
                // default to camelBack key
                $originalKey = $key;
                
                // get the original key if any
                if (isset($this->originalKeys[$key])) {
                    $originalKey = $this->originalKeys[$key];
                }
                
                // store the value with the original key
                $buffer[$originalKey] = $value;
            }
            $this->val = $buffer;
            
            // if the option was serialized serialize it back
            if ($this->isSerialized) {
                $this->val = @serialize($this->val);
            }
        }
        if ($this->val) {
            $this->f->$functionName($this->slug, $this->val);
        }
    }
    
    public function __get($key)
    {
        if ($key == 'val') {
            return $this->val;
        }
        
        // if it's an explicit property then return it
        if (isset($this->$key)) {
            return $this->$key;
        }
        if (!isset($this->val[$key])) {
            return null;
        }
        return $this->val[$key];
    }
    
    public function __set($key, $value)
    {
        if ($key == 'val') {
            $this->val = $value;
            return;
        }
        
        // if val is a string then return
        if (is_string($this->val)) {
            return;
        }
        if (tad_Arr::isAssoc($this->val) or $this->val == array()) {
            $this->val[$key] = $value;
        }
    }
    
    public function setValue($key, $value)
    {
        if (!is_string($key)) {
            throw new BadMethodCallException('Key must be a string', 1);
        }
        $this->val[$key] = $value;
    }
    
    public function getValue($key)
    {
        if (!is_string($key)) {
            throw new BadMethodCallException('Key must be a string', 1);
        }
        if (isset($this->val[$key])) {
            return $this->val[$key];
        }
        return null;
    }
    
    public function reset()
    {
        $this->val = $this->loadedValue;
    }
    
    public function isSiteOption($value = null)
    {
        if (!is_null($value)) {
            if (!is_bool($value)) {
                throw new BadMethodCallException("isSiteOption value must be a boolean", 1);
            }
            $this->isSiteOption = $value;
            return $this;
        } else {
            return $this->isSiteOption;
        }
    }
    
    public function underscoreProperties($value = null)
    {
        if (is_null($value)) {
            return $this->underscoreProperties;
        } else {
            if (!is_bool($value)) {
                throw new BadMethodCallException("Underscore properties value must be a boolean.", 1);
            }
            $this->underscoreProperties = $value;
            return $this;
        }
    }
    public function optionSlug($value = null)
    {
        if (is_null($value)) {
            return $this->slug;
        }
        if (!is_null($value) and !is_string($value)) {
            throw new BadMethodCallException("tad_Option slug must be a string", 1);
        }
        $this->slug = $value;
        return $this;
    }
    public function getValues()
    {
        return $this->val;
    }
    
    public function setValues(array $values)
    {
        $this->val = $values;
    }
    public function removeValue($key)
    {
        if (!is_string($key)) {
            throw new InvalidArgumentException("Key must be a string", 1);
        }
        $this->setValues(tad_Arr::removeKey($this->val, $key)); }
}
