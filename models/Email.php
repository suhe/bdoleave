<?php
namespace app\models;

class Email extends \yii\base\Model{
    
    public static function sendEmail(){
        //register email for user login
        $users[] = $email;
            //create mail
        $query = Helpdesk::find()
        ->select(['employee_id'])
        ->where(['role_id'=>2])
        ->all();
    
        foreach($query as $val){
            $users[] = Employee::getEmployeeEmailById($val->employee_id);
        }
            
        $mail = [];
        $ticket = new Ticket();
        foreach ($users as $user) {
            $mail[] = Yii::$app->mailer->compose('ticket-open',['data' => $ticket->getTicketSingleData($model->getRelationId())]) 
            ->setFrom(Yii::$app->params['mail_user'])
            ->setTo($user)
            ->setSubject(Yii::t('app/message','msg create a new ticket').' '.$model->getRelationId());
        }
        Yii::$app->mailer->sendMultiple($mail);
        Yii::$app->session->setFlash('msg',Yii::t('app/message','msg ticket has been insert'));
    }
}
