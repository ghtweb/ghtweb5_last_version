<?php

namespace app\modules\backend\models\forms;

class GalleryForm extends \Gallery
{
    public function rules()
    {
        $rules = [];

        // title
        $rules[] = ['title', 'filter', 'filter' => 'trim'];
        $rules[] = ['title', 'required'];
        $rules[] = ['title', 'length', 'max' => 255, 'min' => 4];

        // img
        $rules[] = ['img', 'file', 'types' => 'jpg, jpeg, gif, png', 'allowEmpty' => false, 'message' => 'Выберите картинку'];

        // sort
        $rules[] = ['sort', 'filter', 'filter' => 'trim'];
        $rules[] = ['sort', 'required'];
        $rules[] = ['sort', 'numerical', 'integerOnly' => true];

        // status
        $rules[] = ['status', 'required'];
        $rules[] = ['status', 'validators.StatusValidator'];

        return $rules;
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'img' => 'Картинка',
            'status' => 'Статус',
            'sort' => 'Сортировка',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }
}
