<?php

class Status extends CWidget
{
    public $status;
    public $statusText;

    public function run()
    {
        $this->render('index', [
            'status' => $this->status,
            'statusText' => $this->statusText,
        ]);
    }
}
