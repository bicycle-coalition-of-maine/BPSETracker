<?php

namespace app\controllers;

use Yii;

use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use app\models\Invoice;
use app\models\Person;
use app\models\Contact;
use app\models\Rate;
use app\models\Mileage;
use app\models\Config;
use app\models\Event;

/**
 * InvoiceController implements the CRUD actions for Invoice model.
 */
class InvoiceController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Invoice models.
     * @return mixed
     */
    public function actionIndex()
    {
        $query = Invoice::find();
        if(Yii::$app->request->get('show') == 'N') {
            $query = $query->where('approveDate IS NULL');
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Invoice model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Invoice model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $events = Yii::$app->db->createCommand(
                "SELECT E.pkEventID, E.eventDate, O.name AS orgName, E.city
                     , GROUP_CONCAT(ET.eventType ORDER BY ET.eventType SEPARATOR ' + ') AS eventType
                 FROM event E
                 INNER JOIN organization O ON O.pkOrgID = E.fkOrgID
                 INNER JOIN event_event_type EET ON EET.fkEventID = E.pkEventID
                 INNER JOIN event_type ET ON ET.pkEventTypeID = EET.fkEventTypeID
                 WHERE E.eventDate IS NOT NULL
                 GROUP BY E.eventDate, O.name
                 ORDER BY E.eventDate DESC"
                )->queryAll();
        
        $eventDDL = array();
        foreach( $events as $e) {
            $eventDDL[$e['pkEventID']] = "{$e['eventDate']}: {$e['eventType']} @ {$e['orgName']}, {$e['city']}";
        }
        
        $model = new Invoice();
        $model->invoiceDate = date('Y-n-d H:i:s');
        $model->miles = 0;

        if ($model->load(Yii::$app->request->post())) {
            $invDate = Yii::$app->request->post('Invoice')['invoiceDate'];
            $idxSpace = strpos($invDate, ' ');
            $model->invoiceDate = 
                    Yii::$app->globals->formatDateForSQL(substr($invDate, 0, $idxSpace), null)
                    . substr($invDate, $idxSpace);
            if($model->save()) {
                $this->updateEventActuals();
                return $this->redirect(['view', 'id' => $model->pkInvoiceID]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'eventDDL' => $eventDDL,
            'instructorDDL' => Person::find()
                ->select(["CONCAT(lastName, ', ', firstName)"])
                ->indexBy('pkPersonID')
                ->where('isStaff = 1')
                ->orderBy('lastName, firstName')
                ->column(),
            'rateDDL' => Rate::find()
                ->select(["CONCAT('$', rate, ' - ', description)"])
                ->indexBy('pkRateID')
                ->orderBy('rate')
                ->column(),
        ]);
    }

    /**
     * Updates an existing Invoice model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->post('approve') !== null) {
                if(Yii::$app->request->post('approve') == 'yes') {
                    $model->fkApproverID = Yii::$app->user->id;
                    $model->approveDate = date('Y-m-d H:i:s');
                }
                if(Yii::$app->request->post('approve') == 'no') {
                    $model->fkApproverID = Yii::$app->user->id;
                    $model->approveDate = null; // Note that current user is recorded as "unapprover"
                }
            }
            if($model->save()) {
                $this->updateEventActuals($model);
                if($model->approveDate) $this->sendInvoiceApproval($id);                
                return $this->redirect(['view', 'id' => $model->pkInvoiceID]);
            }
        }

        // if approved rate not already set, default it to requested
        if(!$model->hourlyrate) $model->hourlyrate = $model->rateRequested->rate;
        
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Invoice model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Invoice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Invoice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Invoice::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    /*
     * If called without an id, this action shows the current list of 
     * unapproved invoices with a checkmark next to each.
     * If called with an id, set the 3 basic approval fields: approveDate, 
     * fkApproverID, and hourlyrate.
     */
    public function actionApprove($id = null)
    {
        if($id) {
            if(($model = $this->findModel($id)) !== null) {

                $model->fkApproverID = Yii::$app->user->id;
                $model->approveDate = date('Y-m-d H:i:s');
                $model->hourlyrate = $model->rateRequested->rate;

                if($model->save()) {
                    if($model->approveDate) $this->sendInvoiceApproval($id);
                    return $this->redirect(['view', 'id' => $model->pkInvoiceID]);
                }
            }

            // Fall through here if errors
            return $this->render('update', [
                'model' => $model,
            ]);            
        }
        
        else { // Called without ID, just show the list of unapproved invoices
            $dataProvider = new ActiveDataProvider([
                'query' => Invoice::find()
                    ,
            ]);

            return $this->render('index', [
                'dataProvider' => $dataProvider,
            ]);
        }
            
    }
    
    // Update the actuals on the corresponding event, if necessary
    private function updateEventActuals($model)
    {
        $presentations = $model->presentations; if(!$presentations) $presentations = 0;
        $presentees = $model->presentees; if(!$presentees) $presentees = 0;
        if($presentations || $presentees) {
            $event = Event::findOne($model->fkEventID);
            $eventPresentations = $event->presentations_actual; if(!$eventPresentations) $eventPresentations = 0;
            $eventPresentees = $event->participation_actual; if(!$eventPresentees) $eventPresentees = 0;
            if($presentations > $eventPresentations) $event->presentations_actual = $presentations;
            if($presentees > $eventPresentees) $event->participation_actual = $presentees;
            $event->save();
        }        
    }
    private function sendInvoiceApproval($id)
    {
        $invoice = $this->findModel($id);
        $from = ['donotreply@bikemaine.org' => 'BPSE Tracking System'];
        $to = array();
        $cc = array();
        $subject = (Config::findOne('InvApprEmailSubject'))->strValue;
        $body = '';
        
        // Convenience variables
        $submitter = $invoice->instructor;
        $approver = $invoice->approver;
        $event = $invoice->event;
        $eventDescr = "Event for {$event->organization->name} in {$event->city} on {$event->eventDate}";
        $org = $event->organization;
        $contactPerson = $event->contact;
        $contact = Contact::findByKeys($contactPerson->pkPersonID, $org->pkOrgID);
        $rateRequested = $invoice->rateRequested;
        $mileage = Mileage::findOne($event->eventDate);
                
        // Set up email recipients, copying submitter and approver
        $emailConfig = Config::findOne('InvApprEmailRecips');
        $emailConfig->assignEmailRecips($to, $cc);
        $cc[$submitter->email] = "{$submitter->firstName} {$submitter->lastName}";
        $cc[$approver->email] = "{$approver->firstName} {$approver->lastName}";

        // Substitute subject parameters
        $subject = str_replace( '%1', $invoice->pkInvoiceID, $subject);
        $subject = str_replace( '%2', "{$submitter->firstName} {$submitter->lastName}", $subject);
        $subject = str_replace( '%3', "{$approver->firstName} {$approver->lastName}", $subject);
        $subject = str_replace( '%4', $eventDescr, $subject);

        $body = "<p>Invoice #{$invoice->pkInvoiceID} is approved "
            . "on {$invoice->approveDate} "
            . "by <a href='mailto:{$approver->email}'>{$approver->firstName} {$approver->lastName}</a>.</p>";
        if($invoice->approverComments)
            $body .= "<p>{$invoice->approverComments}</p>";
            
        $body .= Yii::$app->globals->formatAsHTMLTable(
            [
                'Instructor Name',
                'Instructor Address',
                'Instructor Phone Number',
                'Organization Visited',
                'Contact Name',
                'Contact Title',
                'Street Address Visited',
                'Town Visited',
                'Zipcode Visited',
                'County Visited',
                'Contact Phone Number',
                'Contact Email',
                'Date of Presentation',
                'Venue',
                'Age of Participants',
                'Topics covered',
                'Type of Event',
                'If "other", please elaborate',
                'Total Number of Presentations Made',
                'Total Number Presented To',
                'Total Hours Presenting',
                'Instructor Compensation Rate',
                'Compensation (Hours x Rate)',
                'Miles Traveled',
                "Travel Reimbursement (Miles x \${$mileage->rate}/mile)",
                'Total Presenter Pay (Compensation + Travel Reimbursement)',
                'Instructor\'s Email (as e-signature)',
                'Supervisor\'s Email',
                'Comments',
            ],
            [
                "<a href='mailto:{$submitter->email}'>{$submitter->firstName} {$submitter->lastName}</a>",
                "{$submitter->address1} {$submitter->address2}<br>{$submitter->city}, {$submitter->state} {$submitter->zipcode}",
                $submitter->formattedPhone('('),
                $org->name,
                "<a href='mailto:{$contactPerson->email}'>{$contactPerson->firstName} {$contactPerson->lastName}</a>",
                $contact->title,
                "{$event->address1} {$event->address2}",
                $event->city,
                $event->zipcode,
                $event->county,
                $contactPerson->formattedPhone('('),
                "<a href='mailto:{$contactPerson->email}'>{$contactPerson->email}</a>",
                Yii::$app->globals->formatSQLDate($event->eventDate, ''),
                $event->isAtOrgAddress ? 'At organization' : 'Off organization grounds',
                $event->ageDescription,
                $event->isBike ? ( $event->isPed ? 'BOTH Bicycle and Pedestrian Safety Training'
                                                 : 'Bicycle Safety Training ONLY'
                                 )
                               : 'Pedestrian Safety Training ONLY',
                $event->eventTypeString,
                $event->otherType,
                $invoice->presentations,
                $invoice->presentees,
                $invoice->hours,
                '$' . number_format($invoice->hourlyrate, 2) 
                    . ' (' 
                    . ($rateRequested->rate == $invoice->hourlyrate 
                        ? $rateRequested->description
                        : 'non-standard') 
                    . ')',
                '$' . number_format($invoice->hours * $invoice->hourlyrate, 2),
                $invoice->miles,
                '$' . number_format($invoice->miles * $mileage->rate, 2),
                '$' . number_format($invoice->hours * $invoice->hourlyrate + $invoice->miles * $mileage->rate, 2),
                "<a href='mailto:{$submitter->email}'>{$submitter->email}</a>",
                "<a href='mailto:{$approver->email}'>{$approver->email}</a>",
                $invoice->submitterComments,
            ]
        );
                
        Yii::$app->mailer->compose()
            ->setFrom($from)
            ->setTo($to)
            ->setCc($cc)
            ->setSubject($subject)
            ->setHtmlBody($body)
            ->send();        
    }
}
