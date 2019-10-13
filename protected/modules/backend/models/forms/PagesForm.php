<?php

namespace app\modules\backend\models\forms;

class PagesForm extends \Pages
{
    public function rules()
    {
        $rules = [];

        // title
        $rules[] = ['title', 'filter', 'filter' => 'trim'];
        $rules[] = ['title', 'required'];
        $rules[] = ['title', 'length', 'max' => 255, 'min' => 4];

        // page
        $rules[] = ['page', 'filter', 'filter' => 'trim'];
        $rules[] = ['page', 'required'];
        $rules[] = ['page', 'length', 'max' => 255, 'min' => 4];
        $rules[] = ['page', 'unique', 'message' => 'Страница уже существует'];
        $rules[] = ['page', 'match',
            'pattern' => '#^([' . \Pages::PAGE_PATTERN . ']+)$#',
            'message' => 'В поле «{attribute}» можно ввести следующие символы "' . \Pages::PAGE_PATTERN . '".'
        ];

        // text
        $rules[] = ['text', 'filter', 'filter' => 'trim'];
        $rules[] = ['text', 'required'];
        $rules[] = ['text', 'length', 'min' => 15];

        // seo_title
        $rules[] = ['seo_title', 'filter', 'filter' => 'trim'];
        $rules[] = ['seo_title', 'length', 'max' => 255];

        // seo_description
        $rules[] = ['seo_description', 'filter', 'filter' => 'trim'];
        $rules[] = ['seo_description', 'length', 'max' => 255];

        // seo_description
        $rules[] = ['seo_keywords', 'filter', 'filter' => 'trim'];
        $rules[] = ['seo_keywords', 'length', 'max' => 255];

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
            'page' => 'Ссылка на страницу',
            'title' => 'Название',
            'text' => 'Текст',
            'seo_title' => 'СЕО заголовок',
            'seo_description' => 'СЕО описание',
            'seo_keywords' => 'СЕО ключевые слова',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
            'status' => 'Статус',
            'lang' => 'Язык',
        ];
    }
}
