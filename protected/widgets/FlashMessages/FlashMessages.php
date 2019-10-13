<?php

class FlashMessages extends CWidget
{
    public $cssClassPrefix = 'alert alert-';


    public function run()
    {
        if ($flashMessages = user()->getFlashes()) {
            foreach ($flashMessages as $key => $message) {
                echo '<div class="' . $this->cssClassPrefix . $key . '">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        ' . $message . '
                    </div>';
            }
        }
    }
}
 