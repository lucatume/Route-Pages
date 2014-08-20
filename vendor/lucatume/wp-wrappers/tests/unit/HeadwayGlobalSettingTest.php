<?php

class GlobalSettingsTest extends PHPUnit_Framework_TestCase
{
    
    const OPTION_NAME = 'headway_option_group_general';
    const DB_ENTRY = 'a:33:{s:31:"merged-default-design-data-core";s:4:"true";s:13:"ran-tour-grid";s:4:"true";s:19:"license-key-headway";s:0:"";s:19:"license-key-qtblock";s:0:"";s:7:"favicon";s:0:"";s:8:"feed-url";s:0:"";s:10:"menu-setup";s:15:"getting-started";s:14:"affiliate-link";s:0:"";s:13:"seo-templates";a:12:{s:5:"index";a:8:{s:5:"title";s:22:"%tagline% | %sitename%";s:11:"description";s:0:"";s:7:"noindex";s:0:"";s:8:"nofollow";s:0:"";s:9:"noarchive";s:0:"";s:9:"nosnippet";s:0:"";s:5:"noodp";s:0:"";s:6:"noydir";s:0:"";}s:11:"single-post";a:8:{s:5:"title";s:20:"%title% | %sitename%";s:11:"description";s:0:"";s:7:"noindex";s:0:"";s:8:"nofollow";s:0:"";s:9:"noarchive";s:0:"";s:9:"nosnippet";s:0:"";s:5:"noodp";s:0:"";s:6:"noydir";s:0:"";}s:11:"single-page";a:8:{s:5:"title";s:20:"%title% | %sitename%";s:11:"description";s:0:"";s:7:"noindex";s:0:"";s:8:"nofollow";s:0:"";s:9:"noarchive";s:0:"";s:9:"nosnippet";s:0:"";s:5:"noodp";s:0:"";s:6:"noydir";s:0:"";}s:17:"single-attachment";a:8:{s:5:"title";s:20:"%title% | %sitename%";s:11:"description";s:0:"";s:7:"noindex";s:0:"";s:8:"nofollow";s:0:"";s:9:"noarchive";s:0:"";s:9:"nosnippet";s:0:"";s:5:"noodp";s:0:"";s:6:"noydir";s:0:"";}s:16:"archive-category";a:8:{s:5:"title";s:20:"%title% | %sitename%";s:11:"description";s:0:"";s:7:"noindex";s:0:"";s:8:"nofollow";s:0:"";s:9:"noarchive";s:0:"";s:9:"nosnippet";s:0:"";s:5:"noodp";s:0:"";s:6:"noydir";s:0:"";}s:14:"archive-search";a:8:{s:5:"title";s:28:"Search: %title% | %sitename%";s:11:"description";s:0:"";s:7:"noindex";s:0:"";s:8:"nofollow";s:0:"";s:9:"noarchive";s:1:"1";s:9:"nosnippet";s:0:"";s:5:"noodp";s:0:"";s:6:"noydir";s:0:"";}s:12:"archive-date";a:8:{s:5:"title";s:20:"%title% | %sitename%";s:11:"description";s:0:"";s:7:"noindex";s:0:"";s:8:"nofollow";s:0:"";s:9:"noarchive";s:0:"";s:9:"nosnippet";s:0:"";s:5:"noodp";s:0:"";s:6:"noydir";s:0:"";}s:14:"archive-author";a:8:{s:5:"title";s:20:"%title% | %sitename%";s:11:"description";s:0:"";s:7:"noindex";s:0:"";s:8:"nofollow";s:0:"";s:9:"noarchive";s:0:"";s:9:"nosnippet";s:0:"";s:5:"noodp";s:0:"";s:6:"noydir";s:0:"";}s:16:"archive-post_tag";a:8:{s:5:"title";s:25:"Tag: %title% | %sitename%";s:11:"description";s:0:"";s:7:"noindex";s:0:"";s:8:"nofollow";s:0:"";s:9:"noarchive";s:0:"";s:9:"nosnippet";s:0:"";s:5:"noodp";s:0:"";s:6:"noydir";s:0:"";}s:16:"archive-taxonomy";a:8:{s:5:"title";s:28:"%title%: %meta% | %sitename%";s:11:"description";s:0:"";s:7:"noindex";s:0:"";s:8:"nofollow";s:0:"";s:9:"noarchive";s:0:"";s:9:"nosnippet";s:0:"";s:5:"noodp";s:0:"";s:6:"noydir";s:0:"";}s:17:"archive-post_type";a:8:{s:5:"title";s:31:"%post_type_plural% | %sitename%";s:11:"description";s:0:"";s:7:"noindex";s:0:"";s:8:"nofollow";s:0:"";s:9:"noarchive";s:0:"";s:9:"nosnippet";s:0:"";s:5:"noodp";s:0:"";s:6:"noydir";s:0:"";}s:6:"four04";a:8:{s:5:"title";s:35:"Whoops! Page Not Found | %sitename%";s:11:"description";s:0:"";s:7:"noindex";s:0:"";s:8:"nofollow";s:0:"";s:9:"noarchive";s:0:"";s:9:"nosnippet";s:0:"";s:5:"noodp";s:0:"";s:6:"noydir";s:0:"";}}s:7:"noindex";s:0:"";s:8:"nofollow";s:0:"";s:9:"noarchive";s:0:"";s:9:"nosnippet";s:0:"";s:5:"noodp";s:0:"";s:6:"noydir";s:0:"";s:27:"nofollow-comment-author-url";s:1:"0";s:14:"header-scripts";s:0:"";s:14:"footer-scripts";s:0:"";s:30:"disable-visual-editor-tooltips";s:1:"0";s:18:"disable-codemirror";s:1:"0";s:14:"grid-safe-mode";s:1:"0";s:25:"layout-selector-safe-mode";s:1:"0";s:15:"disable-caching";s:1:"0";s:28:"remove-dependency-query-vars";s:1:"0";s:11:"enable-gzip";s:1:"1";s:24:"hide-menu-version-number";s:1:"0";s:22:"disable-update-notices";s:1:"0";s:20:"disable-editor-style";s:1:"0";s:10:"debug-mode";s:1:"0";s:15:"ran-tour-design";s:4:"true";s:5:"cache";a:3:{s:2:"ve";a:10:{s:4:"name";s:2:"ve";s:6:"format";s:4:"less";s:9:"fragments";a:8:{i:0;s:102:"/Users/Luca/Dropbox/www/testing/wp-content/themes/headway/library/visual-editor/css/editor-mixins.less";i:1;s:104:"/Users/Luca/Dropbox/www/testing/wp-content/themes/headway/library/visual-editor/css/editor-tooltips.less";i:2;s:95:"/Users/Luca/Dropbox/www/testing/wp-content/themes/headway/library/visual-editor/css/editor.less";i:3;s:111:"/Users/Luca/Dropbox/www/testing/wp-content/themes/headway/library/visual-editor/css/editor-layout-selector.less";i:4;s:102:"/Users/Luca/Dropbox/www/testing/wp-content/themes/headway/library/visual-editor/css/editor-inputs.less";i:5;s:102:"/Users/Luca/Dropbox/www/testing/wp-content/themes/headway/library/visual-editor/css/editor-design.less";i:6;s:100:"/Users/Luca/Dropbox/www/testing/wp-content/themes/headway/library/media/js/codemirror/codemirror.css";i:7;s:103:"/Users/Luca/Dropbox/www/testing/wp-content/themes/headway/library/media/js/codemirror/theme-default.css";}s:12:"dependencies";a:0:{}s:9:"footer-js";b:1;s:7:"enqueue";b:0;s:18:"require-hard-flush";b:1;s:12:"iframe-cache";b:0;s:8:"filename";N;s:4:"hash";N;}s:5:"ve-js";a:10:{s:4:"name";s:5:"ve-js";s:6:"format";s:2:"js";s:9:"fragments";a:21:{i:0;s:84:"/Users/Luca/Dropbox/www/testing/wp-content/themes/headway/library/media/js/jquery.js";i:1;s:88:"/Users/Luca/Dropbox/www/testing/wp-content/themes/headway/library/media/js/underscore.js";i:2;s:90:"/Users/Luca/Dropbox/www/testing/wp-content/themes/headway/library/media/js/itstylesheet.js";i:3;s:83:"/Users/Luca/Dropbox/www/testing/wp-content/themes/headway/library/media/js/jsuri.js";i:4;s:96:"/Users/Luca/Dropbox/www/testing/wp-content/themes/headway/library/media/js/jquery.transitions.js";i:5;s:87:"/Users/Luca/Dropbox/www/testing/wp-content/themes/headway/library/media/js/jquery.ui.js";i:6;s:89:"/Users/Luca/Dropbox/www/testing/wp-content/themes/headway/library/media/js/jquery.qtip.js";i:7;s:91:"/Users/Luca/Dropbox/www/testing/wp-content/themes/headway/library/media/js/jquery.cookie.js";i:8;s:106:"/Users/Luca/Dropbox/www/testing/wp-content/themes/headway/library/media/js/jquery.perfect-scrollbar.min.js";i:9;s:90:"/Users/Luca/Dropbox/www/testing/wp-content/themes/headway/library/media/js/jquery.tabby.js";i:10;s:92:"/Users/Luca/Dropbox/www/testing/wp-content/themes/headway/library/media/js/jquery.hotkeys.js";i:11;s:92:"/Users/Luca/Dropbox/www/testing/wp-content/themes/headway/library/media/js/jquery.taphold.js";i:12;s:98:"/Users/Luca/Dropbox/www/testing/wp-content/themes/headway/library/media/js/jquery.ui.touchpunch.js";i:13;s:89:"/Users/Luca/Dropbox/www/testing/wp-content/themes/headway/library/media/js/colorpicker.js";i:14;s:92:"/Users/Luca/Dropbox/www/testing/wp-content/themes/headway/library/visual-editor/js/editor.js";i:15;s:99:"/Users/Luca/Dropbox/www/testing/wp-content/themes/headway/library/visual-editor/js/editor.inputs.js";i:16;s:97:"/Users/Luca/Dropbox/www/testing/wp-content/themes/headway/library/visual-editor/js/editor.tour.js";i:17;s:102:"/Users/Luca/Dropbox/www/testing/wp-content/themes/headway/library/visual-editor/js/editor.functions.js";i:18;s:97:"/Users/Luca/Dropbox/www/testing/wp-content/themes/headway/library/visual-editor/js/editor.grid.js";i:19;s:102:"/Users/Luca/Dropbox/www/testing/wp-content/themes/headway/library/visual-editor/js/editor.mode.grid.js";i:20;s:104:"/Users/Luca/Dropbox/www/testing/wp-content/themes/headway/library/visual-editor/js/editor.mode.design.js";}s:12:"dependencies";a:0:{}s:9:"footer-js";b:1;s:7:"enqueue";b:1;s:18:"require-hard-flush";b:1;s:12:"iframe-cache";b:0;s:8:"filename";N;s:4:"hash";N;}s:14:"ve-iframe-grid";a:10:{s:4:"name";s:14:"ve-iframe-grid";s:6:"format";s:4:"less";s:9:"fragments";a:9:{i:0;s:85:"/Users/Luca/Dropbox/www/testing/wp-content/themes/headway/library/media/css/reset.css";i:1;s:84:"/Users/Luca/Dropbox/www/testing/wp-content/themes/headway/library/media/css/grid.css";i:2;s:86:"/Users/Luca/Dropbox/www/testing/wp-content/themes/headway/library/media/css/alerts.css";i:3;s:102:"/Users/Luca/Dropbox/www/testing/wp-content/themes/headway/library/visual-editor/css/editor-loading.css";i:4;s:102:"/Users/Luca/Dropbox/www/testing/wp-content/themes/headway/library/visual-editor/css/editor-mixins.less";i:5;s:102:"/Users/Luca/Dropbox/www/testing/wp-content/themes/headway/library/visual-editor/css/editor-iframe.less";i:6;s:104:"/Users/Luca/Dropbox/www/testing/wp-content/themes/headway/library/visual-editor/css/editor-tooltips.less";i:7;s:107:"/Users/Luca/Dropbox/www/testing/wp-content/themes/headway/library/visual-editor/css/editor-iframe-grid.less";i:8;s:117:"/Users/Luca/Dropbox/www/testing/wp-content/themes/headway/library/visual-editor/css/editor-iframe-grid-wireframe.less";}s:12:"dependencies";a:0:{}s:9:"footer-js";b:1;s:7:"enqueue";b:1;s:18:"require-hard-flush";b:1;s:12:"iframe-cache";b:1;s:8:"filename";N;s:4:"hash";N;}}s:28:"some-block-dimensions-limits";s:1:"1";s:32:"some-block-width-constraint-mode";s:1:"1";}';
    
    public function testConstructingOverMissingOptionEntirelyWillExposeEmptyArrayValProperty()
    {
        $mockf = $this->getMockFunctions(array(
            'get_option'
        ));
        $mockf->expects($this->once())->method('get_option')->with(self::OPTION_NAME)->will($this->returnValue(array()));
        $sut = new tad_HeadwayGlobalSettings('someOption', $mockf);
        $this->assertEquals(array() , $sut->val);
    }
    
    protected function getMockFunctions(array $methods)
    {
        return $this->getMock('tad_FunctionsAdapterInterface', array_merge(array(
            '__call'
        ) , $methods));
    }
    
    public function testConstructingWithValidOptionAndNullPrefixWillExposeEmtptyArrayValProperty()
    {
        $mockf = $this->getMockFunctions(array(
            'get_option'
        ));
        $mockf->expects($this->once())->method('get_option')->with(self::OPTION_NAME)->will($this->returnValue(@unserialize(self::DB_ENTRY)));
        
        // create on a prefix that's not there
        $sut = new tad_HeadwayGlobalSettings('someBlock', $mockf);
        $this->assertEquals(array() , $sut->val);
    }
    
    public function testConstructingWithValidOptionAndPrefixWillExposeCamelBackProperties()
    {
        $mockf = $this->getMockFunctions(array(
            'get_option'
        ));
        $mockf->expects($this->once())->method('get_option')->with(self::OPTION_NAME)->will($this->returnValue(@unserialize(self::DB_ENTRY)));
        
        // $this->assertEquals(34, count(array_keys(unserialize(self::DB_ENTRY))));
        $sut = new tad_HeadwayGlobalSettings('some-block-', $mockf);
        $this->assertCount(2, array_keys($sut->val));
        $this->assertEquals(array(
            'dimensionsLimits' => '1',
            'widthConstraintMode' => '1'
        ) , $sut->val);
        $this->assertEquals('1', $sut->dimensionsLimits);
        $this->assertEquals('1', $sut->widthConstraintMode);
    }
    
    public function testConstructingWithNoPrefixWillReturnAllTheSettings()
    {
        $mockf = $this->getMockFunctions(array(
            'get_option'
        ));
        $mockf->expects($this->once())->method('get_option')->with(self::OPTION_NAME)->will($this->returnValue(@unserialize(self::DB_ENTRY)));
        $sut = new tad_HeadwayGlobalSettings('', $mockf);
        $comp = @unserialize(self::DB_ENTRY);
        $cbComp = tad_Arr::camelBackKeys($comp);
        $this->assertCount(count($comp) , $sut->val);
        $this->assertEquals($cbComp, $sut->val);
        foreach ($cbComp as $key => $value) {
            $this->assertArrayHasKey($key, $sut->val);
            $this->assertContains($value, $sut->val);
        }
        foreach ($cbComp as $key => $value) {
            $this->assertEquals($value, $sut->$key);
        }
    }
    
    public function testOnStaticMethodWillAllowFluent()
    {
        $mockf = $this->getMockFunctions(array(
            'get_option'
        ));
        $mockf->expects($this->once())->method('get_option')->with(self::OPTION_NAME)->will($this->returnValue(@unserialize(self::DB_ENTRY)));
        $e = '1';
        $a = tad_HeadwayGlobalSettings::on('some-block-', $mockf)->dimensionsLimits;
        $this->assertEquals($e, $a);
    }
    
    public function testDestructWillNotTryToUpdateTheOption()
    {
        $mockf = $this->getMockFunctions(array(
            'get_option',
            'update_option'
        ));
        $mockf->expects($this->once())->method('get_option')->with(self::OPTION_NAME)->will($this->returnValue(@unserialize(self::DB_ENTRY)));
        $mockf->expects($this->never())->method('update_option');
        $sut = tad_HeadwayGlobalSettings::on('some-block-', $mockf);
        $sut = null;
    }
}
