<?php

class CharacterOnlineStatus extends CWidget
{
    public $online;

    public function run()
    {
        $this->render('index', [
            'online' => $this->online,
        ]);
    }
}
