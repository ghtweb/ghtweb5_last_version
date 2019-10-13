<?php

use app\modules\cabinet\models\forms\TicketsForm;
use app\modules\cabinet\models\search\TicketsSearch;

class TicketsController extends CabinetBaseController
{
    public function actionIndex()
    {
        $this->render('//cabinet/tickets/index', [
            'dataProvider' => (new TicketsSearch())->search(),
        ]);
    }

    public function actionAdd()
    {
        $formModel = new TicketsForm();
        $ticketModel = new Tickets();

        if (isset($_POST[CHtml::modelName($formModel)])) {
            $formModel->setAttributes($_POST[CHtml::modelName($formModel)]);
            if ($formModel->create()) {
                user()->setFlash(FlashConst::MESSAGE_SUCCESS, 'Тикет создан.');
                $this->redirect(['index']);
            }
        }

        $this->render('//cabinet/tickets/add', [
            'formModel' => $formModel,
            'ticketModel' => $ticketModel,
        ]);
    }

    public function actionView($ticket_id)
    {
        $userId = user()->getId();
        $dependency = new \TaggedCache\Dependency([
            new \TaggedCache\Tag(Tickets::class . 'ticket_id:' . $ticket_id . 'user_id:' . $userId),
        ]);

        /** @var Tickets $ticket */
        $ticket = Tickets::model()->cache(3600, $dependency)->find('t.id = :id AND t.user_id = :user_id', [
            ':id' => $ticket_id,
            ':user_id' => $userId,
        ]);

        if ($ticket === null) {
            user()->setFlash(FlashConst::MESSAGE_ERROR, 'Тикет не найден.');
            $this->redirect(['index']);
        }

        // Ответы
        $dependency = new CDbCacheDependency('SELECT MAX(UNIX_TIMESTAMP(created_at)) FROM {{tickets_answers}} WHERE ticket_id = :ticket_id');
        $dependency->params = ['ticket_id' => $ticket_id];
        $model = TicketsAnswers::model()->cache(3600 * 24, $dependency, 2);

        $dataProvider = new CActiveDataProvider($model, [
            'criteria' => [
                'condition' => 'ticket_id = :ticket_id',
                'params' => [':ticket_id' => $ticket->id],
                'order' => 't.created_at DESC',
            ],
            'pagination' => [
                'pageSize' => config('cabinet.tickets.answers.limit'),
                'pageVar' => 'page',
            ],
        ]);

        $model = new TicketsAnswers();

        // При просмотре удаляю статус нового сообщения
        if ($ticket->new_message_for_user == Tickets::STATUS_NEW_MESSAGE_ON) {
            $ticket->new_message_for_user = Tickets::STATUS_NEW_MESSAGE_OFF;
            $ticket->save(false);
        }

        if (isset($_POST['TicketsAnswers']) && $ticket->isStatusOn()) {
            $model->setAttributes($_POST['TicketsAnswers']);
            $model->setAttribute('ticket_id', $ticket_id);
            $model->setAttribute('user_id', user()->getId());
            $model->setAttribute('gs_id', user()->getGsId());

            if ($model->validate()) {
                $model->save(false);

                $ticket->new_message_for_admin = 1;
                $ticket->save(false);

                user()->setFlash(FlashConst::MESSAGE_SUCCESS, 'Ваше сообщение успешно добавлено!');
                $this->refresh();
            }
        }

        $this->render('//cabinet/tickets/view', [
            'ticket' => $ticket,
            'model' => $model,
            'answersDataProvider' => $dataProvider,
        ]);
    }

    public function actionClose($ticket_id)
    {
        /** @var Tickets $ticket */
        $ticket = Tickets::model()->findByPk($ticket_id);

        if ($ticket === null) {
            user()->setFlash(FlashConst::MESSAGE_ERROR, 'Тикет не найден.');
        } elseif ($ticket->user_id != user()->getId()) {
            user()->setFlash(FlashConst::MESSAGE_ERROR, 'Можно закрыть только свой тикет.');
        } elseif ($ticket->isStatusOff()) {
            user()->setFlash(FlashConst::MESSAGE_ERROR, 'Тикет уже закрыт.');
        } else {
            $ticket->status = ActiveRecord::STATUS_OFF;

            if ($ticket->save(false)) {
                user()->setFlash(FlashConst::MESSAGE_SUCCESS, 'Тикет <b>' . CHtml::encode($ticket->title) . '</b> закрыт.');
            } else {
                user()->setFlash(FlashConst::MESSAGE_ERROR, 'Произошла ошибка! Попробуйте повторить позже.');
                Yii::log("Неудалось закрыть тикет\nID: " . $ticket_id . "\n", CLogger::LEVEL_ERROR, 'tickets');
            }
        }

        $this->redirectBack();
    }
}
