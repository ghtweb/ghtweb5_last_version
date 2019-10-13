<?php

class ActiveForm extends CActiveForm
{
    public function errorSummary($models, $header = null, $footer = null, $htmlOptions = [])
    {
        $header = '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
        $footer = '';
        $htmlOptions = array_merge(['class' => 'alert alert-danger'], $htmlOptions);

        return parent::errorSummary($models, $header, $footer, $htmlOptions);
    }

    public static function validate($models, $attributes = null, $loadInput = true)
    {
        $result = [];
        if (!is_array($models))
            $models = [$models];
        foreach ($models as $model) {
            $modelName = CHtml::modelName($model);
            if ($loadInput && isset($_POST[$modelName]))
                $model->attributes = $_POST[$modelName];
            $model->validate($attributes);
            foreach ($model->getErrors() as $attribute => $errors)
                $result[CHtml::activeId($model, $attribute)] = $errors;
        }

        return $result;
    }
}
 