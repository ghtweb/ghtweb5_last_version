<?php

class CabinetModule extends CWebModule
{
    public function init()
    {
        // import the module-level models and components
        $this->setImport([
            'cabinet.models.*',
            'cabinet.components.*',
        ]);
    }

    public function beforeControllerAction($controller, $action)
    {
        if (parent::beforeControllerAction($controller, $action)) {
            // this method is called before any module controller action is performed
            // you may place customized code here
            return true;
        } else
            return false;
    }
}
