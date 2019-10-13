<?php

namespace app\modules\cabinet\models\forms;

class TicketsForm extends \CFormModel
{
    public $category_id;
    public $priority;
    public $date_incident;
    public $char_name;
    public $title;
    public $text;


    public function rules()
    {
        return [
            ['category_id, priority, date_incident, char_name, title, text', 'filter', 'filter' => 'trim'],
            ['category_id, priority, date_incident, title, text', 'required'],
            ['category_id, priority', 'numerical', 'integerOnly' => true],
            ['text', 'length', 'min' => 5],
            ['date_incident', 'length', 'max' => 128],
            ['title', 'length', 'min' => 5, 'max' => 255],
            ['char_name', 'length', 'min' => 3, 'max' => 255],
            ['char_name', 'default', 'value' => null],
            ['category_id', 'in', 'range' => array_keys($this->getCategories()), 'message' => 'Выберите категорию.'],
        ];
    }

    public function getCategories()
    {
        return \CHtml::listData(\TicketsCategories::getOpenCategories(), 'id', 'title');
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Категория',
            'priority' => 'Приоритет',
            'date_incident' => 'Дата происшествия',
            'char_name' => 'Имя персонажа',
            'title' => 'Тема',
            'text' => 'Сообщение',
        ];
    }

    public function create()
    {
        if (!$this->validate()) {
            return false;
        }

        $transaction = db()->beginTransaction();

        try {

            $userId = user()->getId();
            $ticket = new \Tickets();

            $ticket->category_id = $this->category_id;
            $ticket->priority = $this->priority;
            $ticket->date_incident = $this->date_incident;
            $ticket->char_name = $this->char_name;
            $ticket->title = $this->title;
            $ticket->new_message_for_user = \Tickets::STATUS_NEW_MESSAGE_OFF;
            $ticket->new_message_for_admin = \Tickets::STATUS_NEW_MESSAGE_ON;
            $ticket->gs_id = user()->getGsId();
            $ticket->status = \Tickets::STATUS_ON;
            $ticket->user_id = user()->getId();

            $ticket->save(false);


            // Сохраняю переписку для тикета
            $ticketAnswer = new \TicketsAnswers();
            $ticketAnswer->ticket_id = $ticket->id;
            $ticketAnswer->text = $this->text;
            $ticketAnswer->user_id = $userId;
            $ticketAnswer->save(false);

            // Логирую действие юзера
            if (app()->params['user_actions_log']) {
                $log = new \UserActionsLog();
                $log->user_id = user()->getId();
                $log->action_id = \UserActionsLog::ACTION_CREATE_TICKET;
                $log->save(false);
            }

            notify()->adminNoticeTicketAdd([
                'user' => \Users::model()->findByPk(user()->getId()),
                'ticket' => $ticket,
            ]);

            $transaction->commit();

            return true;

        } catch (\Exception $e) {
            $transaction->rollback();
            $this->addError('category_id', $e->getMessage());
        }

        return false;
    }
}
 