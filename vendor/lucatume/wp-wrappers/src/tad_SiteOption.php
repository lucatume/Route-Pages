<?php
class tad_SiteOption extends tad_OptionWrapper
{
    protected $isSiteOption = true;

    public static function on($optionSlug, tad_FunctionsAdapterInterface $f = null)
    {
        $instance = new self($f);
        $instance->optionSlug($optionSlug);
        $instance->isSiteOption(true);
        $instance->load();
        return $instance;
    }
}
