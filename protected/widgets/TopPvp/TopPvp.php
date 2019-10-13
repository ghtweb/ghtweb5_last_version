<?php

class TopPvp extends CWidget
{
    public function init()
    {
        if (($data = cache()->get(CacheNames::TOP_PVP)) === false) {
            if (config('top.pvp.allow') == 1 && (int)config('top.pvp.gs_id') > 0) {
                $data['content'] = [];

                try {
                    $data['content'] = l2('gs', config('top.pvp.gs_id'))->connect()->getTopPvp(config('top.pvp.limit'));

                    if (config('top.pvp.cache') > 0) {
                        cache()->set(CacheNames::TOP_PVP, $data, config('top.pvp.cache') * 60);
                    }
                } catch (Exception $e) {
                    $data['error'] = $e->getMessage();
                }
            }
        }

        app()->controller->renderPartial('//toppvp', $data);
    }
}
 