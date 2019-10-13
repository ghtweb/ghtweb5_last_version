<?php

use app\helpers\Html;

/**
 * Class Step4Form
 *
 * @property string $mysql_host
 * @property int $mysql_port
 * @property string $mysql_user
 * @property string $mysql_pass
 * @property string $mysql_name
 */
class Step2Form extends CFormModel
{
    /**
     * @var string
     */
    public $mysql_host;

    /**
     * @var int
     */
    public $mysql_port;

    /**
     * @var string
     */
    public $mysql_user;

    /**
     * @var string
     */
    public $mysql_pass;

    /**
     * @var string
     */
    public $mysql_name;

    /**
     * Зарпещенные символы в пароле
     * @var array
     */
    private $_mysql_pass_denied_chars = ["'", "\\"];


    public function rules()
    {
        return [
            ['mysql_host, mysql_port, mysql_user, mysql_pass, mysql_name', 'filter', 'filter' => 'trim'],
            ['mysql_host, mysql_port, mysql_user, mysql_name', 'required'],
            ['mysql_port', 'numerical', 'integerOnly' => true],
            ['mysql_pass', 'checkPassChars'],
            ['mysql_pass', 'checkConnect'],
            ['mysql_pass', 'checkFileIsWritable'],
        ];
    }

    /**
     * Пороверка файла database.php на запись
     *
     * @param string $attribute
     * @param array $params
     */
    public function checkFileIsWritable($attribute, array $params)
    {
        if (!$this->hasErrors()) {
            if (!Html::isWritable(Yii::getPathOfAlias('application.config') . DIRECTORY_SEPARATOR . 'database.php')) {
                $this->addError($attribute, 'Необходимо дать файлу protected/config/database.php права на запись 0777');
            }
        }
    }

    public function checkPassChars($attribute)
    {
        if ($this->mysql_pass != '') {
            foreach ($this->_mysql_pass_denied_chars as $char) {
                if (strpos($this->mysql_pass, $char) !== false) {
                    $this->addError($attribute, 'В пароле не должно быть <b>' . $char . '</b> символа');
                }
            }
        }
    }

    public function checkConnect($attribute)
    {
        if (!$this->hasErrors()) {
            try {
                $db = new PDO('mysql:host=' . $this->mysql_host . ';port=' . $this->mysql_port . ';dbname=' . $this->mysql_name, $this->mysql_user, $this->mysql_pass);

                // Проверка таблиц с префиксом ghtweb_
                $res = $db->prepare('SHOW TABLES FROM ' . $this->mysql_name);
                $res->execute();

                $tables = [];

                foreach ($res->fetchAll(PDO::FETCH_COLUMN) as $table) {
                    if (strpos($table, 'ghtweb_') !== false) {
                        $tables[] = $table;
                    }
                }

                if ($tables) {
                    $this->addError('mysql_host', 'Надо удалить следующие таблицы:' . PHP_EOL . '- ' . implode(PHP_EOL . '- ', $tables));
                }

                $db = null;
            } catch (PDOException $e) {
                $this->addError($attribute, $e->getMessage());
            }
        }
    }

    public function attributeLabels()
    {
        return [
            'mysql_host' => 'MYSQL host',
            'mysql_port' => 'MYSQL port',
            'mysql_user' => 'MYSQL user',
            'mysql_pass' => 'MYSQL pass',
            'mysql_name' => 'MYSQL name',
        ];
    }
}
 