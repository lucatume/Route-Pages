<?php
class tad_HeadwayBlockSettingsWrapper
{
    protected $settings;
    protected $data;

    public function __construct($block, $className = null)
    {
        if (is_null($className)) {
            $className =  'tad_StaticHeadwayBlocksData';
        }
        if (!class_exists($className)) {
            return null;
        }
        $block = $className::get_block($block);
        if (is_null($block) or !$block) {
            throw new tad_StaticBadMethodCallException("$block is not a valid block", 1);
        }
        $this->settings = array();
        if (isset($block['settings'])) {
            $this->settings = $block['settings'];
            $this->data = tad_Arr::camelBackKeys($this->settings);
        }
    }
    public static function on($block, $className = null)
    {
        return new self($block, $className);
    }
    public function __get($key)
    {
        if ($key == 'data' or $key == 'settings') {
            return $this->$key;
        }
        if (!tad_Arr::isAssoc($this->settings)) {
            return null;
        }
        if (!array_key_exists($key, $this->settings) and !array_key_exists($key, $this->data)) {
            return null;
        }
        if (array_key_exists($key, $this->data)) {
            return $this->maybeParse($this->data[$key]);
        }
        return null;
    }
    public function getSetting($key, $default = null)
    {
        if (!is_string($key)) {
            throw new tad_StaticBadMethodCallException("Key must be a string", 1);
        }
        if (!is_null($this->$key)) {
            return self::maybeParse($this->$key);
        }
        return $default;
    }
    private function maybeParse($value)
    {
        if (is_null($value)) {
            return null;
        }
        if (in_array($value, array('1', 'true', 'on', 'yes', '0', 'false', 'off', 'no', ''))) {
            return filter_var($value, FILTER_VALIDATE_BOOLEAN);
        }
        return $value;
    }
}
