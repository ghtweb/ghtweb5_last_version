<?php

class CharacterShortInfo extends CWidget
{
    public $charName;
    public $baseClass;
    public $level;

    public function run()
    {
        $this->render('index', [
            'charName' => $this->charName,
            'baseClass' => $this->baseClass,
            'level' => $this->level,
        ]);
    }
}
