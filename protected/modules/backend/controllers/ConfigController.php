<?php

class ConfigController extends BackendBaseController
{
    public function actionIndex()
    {
        $criteria = new CDbCriteria();
        $criteria->order = 't.order';
        $criteria->with = ['config'];

        $model = ConfigGroup::model()->opened()->findAll($criteria);

        // Save
        if (isset($_POST['Config'])) {
            if (isset($_POST['Config'][request()->csrfTokenName])) {
                unset($_POST['Config'][request()->csrfTokenName]);
            }

            foreach ($_POST['Config'] as $k => $v) {
                db()->createCommand()->update('{{config}}', ['value' => $v, 'updated_at' => date('Y-m-d H:i:s')], 'param = :param', [':param' => $k]);
            }

            (new \TaggedCache\Tag(Config::class))->delete();

            if (request()->isAjaxRequest) {
                echo 'ok';
                app()->end();
            }
        }

        if (isset($_POST['Reset'])) {
            $configModel = Config::model()->find('param = :param', [':param' => $_POST['Reset']['field']]);

            if ($configModel !== null) {
                $configModel->setAttribute('value', $configModel->default);
                $configModel->save(false);

                (new \TaggedCache\Tag(Config::class))->delete();

                echo $configModel->default;
            } else {
                echo 'fail';
            }

            app()->end();
        }

        $this->render('//settings/index', [
            'model' => $model,
        ]);
    }

    public function actionSort()
    {
        $groupId = (int) request()->getParam('groupId', 0);
        $data = request()->getParam('data');

        if ($groupId < 1 || !$data) {
            die;
        }

        foreach (explode(',', $data) as $v) {
            list($id, $order) = explode('-', $v);

            db()->createCommand()->update('{{config}}', ['order' => $order], 'id = :id', ['id' => $id]);
        }

        $this->ajax['status'] = 'success';
        echo json_encode($this->ajax);
        app()->end();
    }
}