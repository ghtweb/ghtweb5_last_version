<?php

class DbConfig extends CApplicationComponent
{
    protected $_data = [];
    protected $_cacheTime = 86400;


    public function init()
    {
        $dependency = new \TaggedCache\Dependency([
            new \TaggedCache\Tag(Config::class)
        ]);

        $items = Config::model()
            ->cache($this->_cacheTime, $dependency)
            ->findAll();

        foreach ($items as $item) {
            if ($item['param']) {
                $this->_data[$item['param']] = ($item['value'] === '' ? $item['default'] : $item['value']);
            }
        }

        parent::init();
    }

    public function get($key, $default = null)
    {
        return isset($this->_data[$key]) ? $this->_data[$key] : $default;
    }

    public function set($key, $value)
    {
        $command = Yii::app()->db->createCommand();

        $res = $command->update('{{config}}', [
            'value' => $value,
            'updated_at' => date('Y-m-d H:i:s'),
        ], 'param = :param', ['param' => $key]);

        // Запись была сделана
        if ($res === 1) {
            $this->_data[$key] = $value;
        }
    }

    public function add($params)
    {
        if (isset($params[0]) && is_array($params[0])) {
            foreach ($params as $item) {
                $this->createParameter($item);
            }
        } elseif ($params) {
            $this->createParameter($params);
        }
    }

    public function delete($key)
    {
        if (is_array($key)) {
            foreach ($key as $item) {
                $this->removeParameter($item);
            }
        } elseif ($key) {
            $this->removeParameter($key);
        }
    }

    protected function createParameter($param)
    {
        if (!empty($param['param'])) {
            $command = Yii::app()->db->createCommand();

            $res = $command->insert('{{config}}', [
                'param' => $param['param'],
                'label' => isset($param['label']) ? $param['label'] : $param['param'],
                'value' => isset($param['value']) ? $param['value'] : '',
                'default' => isset($param['default']) ? $param['default'] : '',
                'field_type' => isset($param['field_type']) ? $param['field_type'] : 'textField',
            ]);

            if ($res) {
                $this->_data[$param['param']] = (isset($param['value']) ? $param['value'] : (isset($param['default']) ? $param['default'] : ''));
            }
        }
    }

    protected function removeParameter($key)
    {
        if (!empty($key)) {
            $res = Yii::app()->db->createCommand()->delete('{{config}}', 'param = :param', ['param' => $key]);

            if ($res === 1 && isset($this->_data[$key])) {
                unset($this->_data[$key]);
            }
        }
    }
}
