<?php

namespace app\modules\backend\models\forms;

class NewsForm extends \News
{
    public function init()
    {
        $this->attachEventHandler('onBeforeSave', [$this, 'generateSlug']);
        parent::init();
    }

    public function rules()
    {
        $rules = [];

        // title
        $rules[] = ['title', 'filter', 'filter' => 'trim'];
        $rules[] = ['title', 'required'];
        $rules[] = ['title', 'length', 'max' => 255, 'min' => 4];

        // description
        $rules[] = ['description', 'filter', 'filter' => 'trim'];
        $rules[] = ['description', 'required'];
        $rules[] = ['description', 'length', 'min' => 15];

        // text
        $rules[] = ['text', 'filter', 'filter' => 'trim'];
        $rules[] = ['text', 'required'];
        $rules[] = ['text', 'length', 'min' => 15];

        // img
        $rules[] = ['img', 'file', 'types' => 'jpg, jpeg, gif, png', 'allowEmpty' => true];

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
            'title' => 'Название',
            'description' => 'Короткое описание',
            'text' => 'Текст',
            'seo_title' => 'СЕО заголовок',
            'seo_description' => 'СЕО описание',
            'seo_keywords' => 'СЕО ключевые слова',
            'status' => 'Статус',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
            'img' => 'Картинка',
        ];
    }

    public function generateSlug(\CEvent $e)
    {
        /** @var \News $model */
        $model = $e->sender;

        if ($model->scenario == 'insert' || empty($model->slug)) {
            $slugify = new \Cocur\Slugify\Slugify();
            $title = $model->title;
            $model->slug = $slugify->slugify($title);
        }
    }
}
