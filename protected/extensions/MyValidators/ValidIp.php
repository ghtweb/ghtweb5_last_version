<?php

class ValidIp extends CValidator
{
    protected function validateAttribute($object, $attribute)
    {
        if ($object->{$attribute}) {
            $ipList = explode(PHP_EOL, $object->{$attribute});
            $ipList = array_filter($ipList, 'trim');

            if ($ipList) {
                foreach ($ipList as $ip) {
                    if (!ip2long($ip)) {
                        $object->addError($attribute, $ip . ' - не является верным IP адресом.');
                    }
                }
            }
        }
    }
}
 