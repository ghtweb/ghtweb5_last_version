<?php

namespace app\services;

class VersionService
{
    private $_version;

    public function getVersion()
    {
        if (is_null($this->_version)) {
            $path = \Yii::getPathOfAlias('application.config') . '/version.json';
            if (!is_file($path) && !is_readable($path)) {
                return 0;
            }
            $version = @file_get_contents($path);
            $version = json_decode($version, true);
            if (!empty($version['version'])) {
                $this->_version = $version['version'];
            }
        }
        return $this->_version ? $this->_version : 0;
    }
}
