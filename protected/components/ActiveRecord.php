<?php

class ActiveRecord extends CActiveRecord
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_SEARCH = 'search';


    const STATUS_ON = 1; // Открыта
    const STATUS_OFF = 0; // Скрыта
    const STATUS_DELETED = -1; // Удалена


    /**
     * @param null $className
     *
     * @return ActiveRecord
     */
    public static function model($className = null)
    {
        return parent::model($className ?: get_called_class());
    }

    protected function beforeSave()
    {
        if ($this->isNewRecord) {
            if ($this->hasAttribute('created_at')) {
                $this->setAttribute('created_at', date('Y-m-d H:i:s'));
            }
        }

        if ($this->hasAttribute('updated_at')) {
            $this->setAttribute('updated_at', date('Y-m-d H:i:s'));
        }

        return parent::beforeSave();
    }

    public function scopes()
    {
        return [
            // Выберет только открытые
            'opened' => [
                'condition' => $this->tableAlias . '.status = :opened_status',
                'params' => ['opened_status' => ActiveRecord::STATUS_ON],
            ],
            // Выберет только закрытые
            'closed' => [
                'condition' => $this->tableAlias . '.status = :closed_status',
                'params' => ['closed_status' => ActiveRecord::STATUS_OFF],
            ],
            // Выберет только удаленные
            'deleted' => [
                'condition' => $this->tableAlias . '.status = :deleted_status',
                'params' => ['deleted_status' => ActiveRecord::STATUS_DELETED],
            ],
            // Выберет все кроме удаленных
            'not_deleted' => [
                'condition' => $this->tableAlias . '.status != :not_deleted_status',
                'params' => ['not_deleted_status' => ActiveRecord::STATUS_DELETED],
            ],
        ];
    }

    public function getStatusList()
    {
        return [
            self::STATUS_ON => 'Вкл',
            self::STATUS_OFF => 'Выкл',
            self::STATUS_DELETED => 'Удален',
        ];
    }

    /**
     * @return array
     */
    public static function getStatusListWithoutDelete()
    {
        return [
            self::STATUS_ON => 'Вкл',
            self::STATUS_OFF => 'Выкл',
        ];
    }

    public function getStatus()
    {
        $data = $this->getStatusList();

        return isset($data[$this->status]) ? $data[$this->status] : '*Unknown*';
    }

    public function isStatusOn()
    {
        return $this->status == self::STATUS_ON;
    }

    public function isStatusOff()
    {
        return $this->status == self::STATUS_OFF;
    }

    public function isStatusDel()
    {
        return $this->status == self::STATUS_DELETED;
    }

    /**
     * @param string $format
     * @return string
     */
    public function getCreatedAt($format = 'Y-m-d H:i:s')
    {
        $dateTime = new DateTime($this->getAttribute('created_at'));

        return $dateTime->format($format);
    }

    /**
     * @param string $format
     * @return string
     */
    public function getUpdatedAt($format = 'Y-m-d H:i:s')
    {
        $dateTime = new DateTime($this->getAttribute('updated_at'));

        return $dateTime->format($format);
    }
}
