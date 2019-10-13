<?php

class ClearCacheBehavior extends CBehavior
{
    public function events()
    {
        return array_merge(parent::events(), [
            'onAfterSave' => 'clearCache',
            'onAfterDelete' => 'clearCache',
        ]);
    }

    /**
     * @var string
     */
    public $className;

    /**
     * @var array
     */
    public $idAttributeName;

    public function clearCache($event)
    {
        if (!$this->idAttributeName) {
            (new \TaggedCache\Tag($this->className))->delete();
            return;
        }

        if (!is_array($this->idAttributeName)) {
            $this->idAttributeName = [$this->idAttributeName];
        }

        $allFields = '';

        foreach ($this->idAttributeName as $attribute) {
            $allFields .= $attribute . ':' . $event->sender->{$attribute};
            (new \TaggedCache\Tag($this->className . $attribute . ':' . $event->sender->{$attribute}))->delete();
        }

        (new \TaggedCache\Tag($this->className))->delete();
        (new \TaggedCache\Tag($this->className . $allFields))->delete();
    }
}
