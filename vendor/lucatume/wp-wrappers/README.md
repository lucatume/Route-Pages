# WordPress Wrappers

A small set of classes wrapping WordPress common operations.

## Options
This class allows for creating, reading and updating an options stored in the database. It's a wrapper around the <code>update_option</code> and <code>load_option</code> methods.  
It will create and read array options and keys will be made available using the camelBack format.

    // get the 'my_theme' option from the database
    $the = tad_OptionWrapper::on('my_theme');

    // if the option defines 'header_text', 'footer_color' and 'sidebar_position'
    $headerText = $the->headerText;
    $footerColor = $the->footerColor;
    $sidebarPosition = $the->sidebarPosition;

## Serialized tad_OptionWrapper
Much like the tad_OptionWrapper class but without writing support at the moment. Actually feeding it a non-serialized option will not be a problem.

## Theme support
Allows for quick addition and removal of theme support in WordPress.

    // add HTML5 search form support
    tad_ThemeSupport::addSupport('html5', 'search-form');

    // remove the support
    tad_ThemeSupport::removeSupport('html5', 'search-form');