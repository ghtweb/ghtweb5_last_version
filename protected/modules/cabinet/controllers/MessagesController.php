<?php

use app\modules\cabinet\models\search\MessagesSearch;
use TaggedCache\Dependency;
use TaggedCache\Tag;

class MessagesController extends CabinetBaseController
{
    public function actionIndex()
    {
        $this->render('//cabinet/messages/index', [
            'dataProvider' => (new MessagesSearch())->search(),
        ]);
    }

    public function actionDetail($id)
    {
        $userId = user()->getId();

        $dependency = new Dependency([
            new Tag(\UserMessages::class . 'id:' . $id . 'user_id:' . $userId)
        ]);

        /** @var UserMessages $model */
        $model = UserMessages::model()->cache(3600 * 24, $dependency)->findByPk($id, 'user_id = :user_id', ['user_id' => $userId]);

        if (!$model) {
            throw new CHttpException(404);
        }

        // Меняю статус на прочитаный
        if ($model->read == UserMessages::STATUS_NOT_READ) {
            $model->read = UserMessages::STATUS_READ;
            $model->save(false);
        }

        $this->render('//cabinet/messages/detail', [
            'model' => $model,
        ]);
    }
}
