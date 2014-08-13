<?php

class ActivationAndInstallationRequirementsCest
{
    protected  $shouldRestoreWpRouterMainFile = false;
    protected $wpRouterMainFile;

    public function _before()
    {
        $this->wpRouterMainFile = dirname(__FILE__) . '/../../../WP-Router/wp-router';
    }

    public function _after()
    {
        if(!file_exists($this->wpRouterMainFile . '.php')){
            $this->shouldRestoreWpRouterMainFile = true;
        }
        if($this->shouldRestoreWpRouterMainFile){
            rename($this->wpRouterMainFile, $this->wpRouterMainFile. '.php');
        }
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

   protected function disinstallWpRouterPlugin(){
       if(file_exists($this->wpRouterMainFile . '.php')){
           $this->shouldRestoreWpRouterMainFile = rename($this->wpRouterMainFile . '.php', $this->wpRouterMainFile);
       }
   }
}