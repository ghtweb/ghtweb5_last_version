<?php

use app\modules\backend\models\search\TicketsSearch;

class TicketsController extends BackendBaseController
{
    public function actionIndex()
    {
        /** @var TicketsSearch $model */
        $model = $this->loadModel(TicketsSearch::class);
        $model->unsetAttributes();

        if (isset($_GET[CHtml::modelName($model)])) {
            $model->setAttributes($_GET[CHtml::modelName($model)]);
        }

        $dataProvider = $model->search();

        $this->render('//tickets/index', [
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    public function actionEdit($id)
    {
        /** @var Tickets $ticket */
        $ticket = Tickets::model()->with(['category', 'user'])->findByPk($id);

        if ($ticket === null) {
            user()->setFlash(FlashConst::MESSAGE_ERROR, 'Тикет не найден.');
            $this->redirect(['index']);
        }

        if (!$ticket->user) {
            user()->setFlash(FlashConst::MESSAGE_ERROR, 'Автор тикета не найден.');
            $this->redirect(['index']);
        }

        // Убираю статус нового сообщения
        if ($ticket->new_message_for_admin == Tickets::STATUS_NEW_MESSAGE_ON) {
            $ticket->new_message_for_admin = 0;
            $ticket->save(false, ['new_message_for_admin', 'updated_at']);
        }

        // Ответы
        $answersDataProvider = new CActiveDataProvider('TicketsAnswers', [
            'criteria' => [
                'condition' => 'ticket_id = :ticket_id',
                'params' => ['ticket_id' => $ticket->id],
                'order' => 't.created_at DESC',
                'with' => 'userInfo'
            ],
            'pagination' => [
                'pageSize' => 10,
                'pageVar' => 'page',
            ],
        ]);

        $model = new TicketsAnswers();

        if (isset($_POST['TicketsAnswers'])) {
            $model->setAttributes($_POST['TicketsAnswers']);
            $model->ticket_id = $id;
            $model->user_id = admin()->getId();

            if ($model->save()) {
                // change new message status
                $ticket->new_message_for_user = 1;

                $ticket->save(false, ['new_message_for_user', 'updated_at']);

                notify()->userNoticeTicketAnswer($ticket->user->email, [
                    'ticket' => $ticket,
                    'user' => $ticket->user,
                ]);

                user()->setFlash(FlashConst::MESSAGE_SUCCESS, 'Ответ добавлен.');
                $this->refresh();
            }
        }

        $this->render('//tickets/edit', [
            'ticket' => $ticket,
            'model' => $model,
            'answersDataProvider' => $answersDataProvider,
        ]);
    }
}