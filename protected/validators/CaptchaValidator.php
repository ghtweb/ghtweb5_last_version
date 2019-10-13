<?php

class CaptchaValidator extends CCaptchaValidator
{
    protected function validateAttribute($object, $attribute)
    {
        if (!$object->hasErrors($attribute)) {
            $value = $object->$attribute;

            if ($this->allowEmpty && $this->isEmpty($value)) {
                return;
            }

            $captcha = $this->getCaptchaAction();

            if (is_array($value) || !$captcha->validate($value, $this->caseSensitive)) {
                $message = $this->message !== null ? $this->message : 'Код с картинки введен не верно.';
                $this->addError($object, $attribute, $message);
            }
        }
    }
}

