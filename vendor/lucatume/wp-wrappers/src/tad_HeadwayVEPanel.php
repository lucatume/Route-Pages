<?php
class tad_HeadwayVEPanel
{
    const PRIORITY = 1000;

    protected $functions;
    protected $className;

    public function __construct($className, tad_FunctionsAdapterInterface $functions = null)
    {
        if (!is_string($className)) {
            throw new BadMethodCallException("Class name must be a string", 1);
        }
        if (is_null($functions)) {
            $functions = new tad_FunctionsAdapter();
        }
        $this->functions = $functions;
        $this->className = $className;
        $this->functions->add_action('after_setup_theme', array($this, 'register'));
    }
    public function register()
    {
        if (!class_exists('Headway')) {
            return false;
        }
        $tag = 'headway_visual_editor_display_init';
        // hook in with a priority higher than the one Headway registers
        // its own setup block to have the Header Image options panel show
        // on the right side of it
        $this->functions->add_action($tag, create_function('', 'return headway_register_visual_editor_panel_callback(\'' . $this->className . '\');'), self::PRIORITY);
        return true;
    }
    public static function on($className, tad_FunctionsAdapterInterface $functions = null)
    {
        return new self($className, $functions);
    }
}
