<?php

class StatusValidator extends CRangeValidator
{
    protected function validateAttribute($object, $attribute)
    {
        $this->range = array_keys(ActiveRecord::getStatusListWithoutDelete());
        $this->allowEmpty = false;

        parent::validateAttribute($object, $attribute);
    }
}

