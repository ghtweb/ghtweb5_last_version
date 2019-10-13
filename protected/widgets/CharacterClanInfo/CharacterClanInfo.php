<?php

class CharacterClanInfo extends CWidget
{
    public $clanId;
    public $gs;
    public $clanCrest;
    public $clanName;
    public $showClanLink;

    public function run()
    {
        $this->render('index', [
            'clanId' => $this->clanId,
            'gs' => $this->gs,
            'clanCrest' => $this->clanCrest,
            'clanName' => $this->clanName,
            'showClanLink' => $this->showClanLink,
        ]);
    }
}
