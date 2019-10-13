<?php

class TopPk extends CWidget
{
    public function init()
    {
        if (($data = cache()->get(CacheNames::TOP_PK)) === false) {
            if (config('top.pk.allow') == 1 && (int)config('top.pk.gs_id') > 0) {
                $data['content'] = [];

                try {
                    $data['content'] = l2('gs', config('top.pk.gs_id'))->connect()->getTopPk(config('top.pk.limit'));;

                    if (config('top.pk.cache') > 0) {
                        cache()->set(CacheNames::TOP_PK, $data, config('top.pk.cache') * 60);
                    }
                } catch (Exception $e) {
                    $data['error'] = $e->getMessage();
                }
            }
        }

        app()->controller->renderPartial('//toppk', $data);
    }
}
 