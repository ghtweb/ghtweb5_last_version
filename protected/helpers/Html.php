<?php

namespace app\helpers;

class Html
{
    public static function errorSummary($model, $className = 'danger')
    {
        return \CHtml::errorSummary($model, '', '', [
            'class' => 'alert alert-' . $className,
        ]);
    }

    /**
     * Проверяет доступность папки/файла на запись
     *
     * @param string $path
     *
     * @return bool
     */
    public static function isWritable($path)
    {
        return is_writable($path) || @chmod($path, 0777) && is_writable($path);
    }
}
