<?php

class ActivationAndInstallationRequirementsCest
{
    protected  $shouldRestoreWpRouterMainFile = false;
    protected $wpRouterMainFile;

    public function _before()
    {
        $this->wpRouterMainFile = dirname(__FILE__) . '/../../../WP-Router/wp-router';
        $this->maybeRestoreWpRouterFileExtension();
    }

    public function _after()
    {
        $this->maybeRestoreWpRouterFileExtension();
    }

    public function shouldShowWpDiePageIfWPRouterNotInstalled(AcceptanceTester $I)
    {
        $this->disinstallWpRouterPlugin();
        $I->loginAsAdmin();
        $I->amOnPluginsPage();
        $I->dontSeePluginInstalled('wp-router');

        $I->activatePlugin('route-pages');

        $I->seeWpDiePage();
        $I->seeElement('a#wp-router-installation-link');
    }
    public function shouldShowWpDiePageIfWPRouterNotActivated(AcceptanceTester $I){
        $I->loginAsAdmin();
        $I->amOnPluginsPage();
        $I->canSeePluginDeactivated('wp-router');

        $I->activatePlugin('route-pages');

        $I->seeWpDiePage();
        $I->seeElement('a#wp-router-activation-link');
    }

   protected function disinstallWpRouterPlugin(){
       if(file_exists($this->wpRouterMainFile . '.php')){
           $this->shouldRestoreWpRouterMainFile = rename($this->wpRouterMainFile . '.php', $this->wpRouterMainFile);
       }
   }

    protected function maybeRestoreWpRouterFileExtension()
    {
        if (!file_exists($this->wpRouterMainFile . '.php')) {
            $this->shouldRestoreWpRouterMainFile = true;
        }
        if ($this->shouldRestoreWpRouterMainFile) {
            rename($this->wpRouterMainFile, $this->wpRouterMainFile . '.php');
            $this->shouldRestoreWpRouterMainFile = false;
        }
    }
}