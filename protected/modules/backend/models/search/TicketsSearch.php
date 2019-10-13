<?php

namespace app\modules\backend\models\search;

class TicketsSearch extends \Tickets
{
    public function rules()
    {
        $rules = [];

        // id
        $rules[] = ['id', 'numerical', 'integerOnly' => true];

        // title
        $rules[] = ['title', 'filter', 'filter' => 'trim'];

        // page
        $rules[] = ['page', 'filter', 'filter' => 'trim'];

        // status
        $rules[] = ['status', 'in', 'range' => array_keys(\ActiveRecord::getStatusListWithoutDelete())];

        return $rules;
    }

    public function search()
    {
        $criteria = new \CDbCriteria;

        $criteria->compare('id', $this->title);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('category_id', $this->category_id);
        $criteria->compare('priority', $this->priority);
        $criteria->compare('status', $this->status);
        $criteria->compare('new_message_for_admin', $this->new_message_for_admin);
        $criteria->compare('gs_id', $this->gs_id);

        $criteria->order = 't.new_message_for_admin DESC, t.created_at';
        $criteria->with = ['user'];

        return new \CActiveDataProvider($this, [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => 20,
                'pageVar' => 'page',
            ],
        ]);
    }

    /**
     * @return array
     */
    public function getGsList()
    {
        return \CHtml::listData(\Gs::getOpenServers(), 'id', 'name');
    }

    /**
     * Список категорий
     *
     * @return array
     */
    public function getCategories()
    {
        return \CHtml::listData(\TicketsCategories::getOpenCategories(), 'id', 'title');
    }
}
