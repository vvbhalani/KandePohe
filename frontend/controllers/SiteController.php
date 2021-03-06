<?php
namespace frontend\controllers;

use common\models\otherlibraries\Compressimage;
use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
#use frontend\components\CommonHelper;
use common\components\CommonHelper;
use common\models\User;
use common\models\PartenersReligion;
use yii\widgets\ActiveForm;
use yii\web\Response;


/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new User();
        $model->scenario = User::SCENARIO_REGISTER;
        return $this->render('index',
            ['model' => $model]
        );
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
         //die('hii');
        if (!Yii::$app->user->isGuest) {
            //return $this->goHome();
            $this->redirect(['user/dashboard']);
        }

        $model = new LoginForm();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()))
        {
            
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
            Yii::$app->end();
        }
            
        if ($model->load(Yii::$app->request->post()) && $model->login()) {

            $this->redirect(['user/dashboard']);
        } else {

        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionRegister() {
        //$model = new Registration();
        $model = new User;
        $model->scenario = User::SCENARIO_REGISTER;
        // AJAX Validation
        #echo "virkant";exit;
        //if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()))
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {

            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
            Yii::$app->end();
        }


        if ($model->load(Yii::$app->request->post()) ) {
            #echo " <br> 2 ";

            //get verify response data
            $password = $model->password_hash;
            $email = $model->email;
            $model->password_hash=$model->setPassword($password);
            $model->repeat_password=$model->password_hash;
            $model->toc=$model->toc[0];
            $model->status= 1;
            $region_id = $model->iReligion_ID;
            $U_R_ID = $model->generateUniqueRandomNumber(9);
            $model->Registration_Number = $U_R_ID;
            $model->completed_step = $model->setCompletedStep('1');
            /*$model->save();
            var_dump($model->errors);
            die();*/
            if($model->save()){

                $OUTPUT ='';
                #$OUTPUT ='';
                $EMAIL_ID = $model->email;
                $OUTPUT .='
                          <div class="row">
                            <div class="col-sm-10 col-sm-offset-1 text-center">
                              <h4 class="mrg-bt-30 text-dark">'.$EMAIL_ID.'</h4>
                              <h4 class="mrg-bt-30"><span class="text-success"><strong>&#10003;</strong></span>
                              A confirmation email was sent to '.$EMAIL_ID.'
                              To confirm your account, please click the link in the message.
                              If you do not see the email in your Inbox, please check your Spam box.
                            </h4>
                            </div>
                          </div>';
                $UID = $model->id;
                #$activation_link = Yii::$app->urlManager->createAbsoluteUrl(['site/activationaccount','id'=> base64_encode($model->id)]);
                $activation_link = '';
                $activation_link .= Yii::$app->urlManager->createAbsoluteUrl(['site/activeaccount']);
                $activation_link .= "?id=".base64_encode($model->id);
                #$activation_link1 = Yii::$app->urlManager->createUrl(['site/activeaccount']);
                #$activation_link1 = $activation_link1."?id=".base64_encode($model->id);
                #$OUTPUT .= $activation_link1;
                //$return = array('status' => 200,'message' => 'success','OUTPUT'=>$OUTPUT);
                $to      = $model->email;

                $subject = 'Please verify your Account On Kande-pohe.com';
                $message = "";
                #$message .= 'hi '."\r\n";
                $message .= "Dear ".$model->First_Name.",
                        You've entered ".$to." as the email address for your Kande-pohe.com Account. To start using Shaadi profile, confirm your email by clicking the button below.
                        Verify Your Account from below URL.
                        Alternatively, copy and paste the below URL in a new browser window instead and hit Enter.
                        ";
                $message .= $activation_link;
                // $headers = 'From: no-replay@vcodertechnolab.com' . "\r\n" .'Reply-To: webmaster@example.com' . "\r\n" .'X-Mailer: PHP/' . phpversion();
                #mail($to, $subject, $message, $headers);
                $res = Yii::$app->mailer->compose()
                    ->setTo($email)
                    ->setFrom('no-replay@vcodertechnolab.com')
                    ->setSubject($subject)
                    ->setTextBody($message)
                    ->send();

                $model1 = new LoginForm();
                $model1->password = $password;
                $model1->email = $email;

                if ($model1->login()) {
                    $this->redirect(['site/basic-details']);
                } else {

                }

            }
            else {
                $this->goHome();

            }


        }else{
            $OUTPUT ='';
            #$OUTPUT1 ='';
            $OUTPUT .='
                          <div class="row">
                            <div class="col-sm-10 col-sm-offset-1 text-center">
                              <h4 class="mrg-bt-30 text-dark"></h4>
                              <h4 class="mrg-bt-30"><span class="text-success"><strong>&#735;</strong></span>
                              There is something wrong with signup
                            </h4>
                            </div>
                          </div>';
            //$return = array('status' => 200, 'message' => 'error', 'OUTPUT' => $OUTPUT);
        }

        //Yii::$app->response->format = Response::FORMAT_JSON;
        // return $return;
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        //$model->scenario = User::SCENARIO_SFP;
        $commonhelper = new CommonHelper();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()))
        {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $returnData = ActiveForm::validate($model);

            if(Yii::$app->request->post('vStatus')==1 && count($returnData)==0){
                if ($model->sendEmail()) {
                    $returnData['status'] = 1;
                    $returnData['email'] = Yii::$app->request->post('PasswordResetRequestForm')['email'];
                }
                else {
                    $returnData['status'] = 0;
                }
                
            }
            else {
                $returnData['status'] = 0;
            }
            return $returnData;
            Yii::$app->end();
        }
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        $commonhelper = new CommonHelper();
        //$id = $commonhelper->encryptor('decrypt',Yii::$app->request->get('id'));
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        
        
        if($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()){
            Yii::$app->session->setFlash('success', 'New password was saved.');
            return $this->goHome();
        }
        return $this->render('reset-password',[
            'model' => $model
        ]);
        
    }

    public function actionActiveaccount($id){
        $id = base64_decode($id);
        if($model = User::findOne($id)){
            $model->scenario = User::SCENARIO_FIRST_VERIFICATION;
            $model->eFirstVerificationMailStatus = 'YES';
            if($model->save($model)){
                $this->redirect(['site/basic-details']);
            }else{
                #$this->redirect('index.php');
            }

        }else{
            #$this->redirect('index.php');
        }

    }
    public function actionBasicDetails($id='')
    {
        if (!Yii::$app->user->isGuest) {
            #$id = base64_decode($id);
            $id = Yii::$app->user->identity->id;
            #   $id = base64_decode($id);

            if($model = User::findOne($id)){

                $model->scenario = User::SCENARIO_REGISTER1;
                if($model->load(Yii::$app->request->post())){
                    //$model->save();
                    $model->completed_step = $model->setCompletedStep('2');
                    if($model->save()){
                        $this->redirect(['site/education-occupation']);
                    }
                }
                return $this->render('register1',[
                    'model' => $model
                ]);
            }else{
                return $this->redirect(Yii::getAlias('@web'));
            }
        }else{
            return $this->redirect(Yii::getAlias('@web'));
        }
    }


    public function actionEducationOccupation($id='')
    {
        if (!Yii::$app->user->isGuest) {
            #$id = base64_decode($id);
            $id = Yii::$app->user->identity->id;
            #   $id = base64_decode($id);

            if($model = User::findOne($id)){

                $model->scenario = User::SCENARIO_REGISTER2;
                if($model->load(Yii::$app->request->post())){
                    $model->completed_step = $model->setCompletedStep('3');
                    if($model->save()){

                        $this->redirect(['site/life-style']);
                    }
                }
                return $this->render('register2',[
                    'model' => $model
                ]);

            }else{
                return $this->redirect(Yii::getAlias('@web'));
            }
        }else{
            return $this->redirect(Yii::getAlias('@web'));
        }
    }

    public function actionLifeStyle($id='')
    {
        if (!Yii::$app->user->isGuest) {
            #$id = base64_decode($id);
            $id = Yii::$app->user->identity->id;
            #   $id = base64_decode($id);

            if($model = User::findOne($id)){

                $model->scenario = User::SCENARIO_REGISTER3;

                if($model->load(Yii::$app->request->post())){
                    #echo "<pre>"; print_r($model->scenario);exit;
                    $model->completed_step = $model->setCompletedStep('4');
                    if($model->save()){

                        $this->redirect(['site/about-family']);
                    }
                }
                return $this->render('register3',[
                    'model' => $model
                ]);

            }else{
                return $this->redirect(Yii::getAlias('@web'));
            }
        }else{
            return $this->redirect(Yii::getAlias('@web'));
        }

    }
    public function actionAboutFamily($id='')
    {
        if (!Yii::$app->user->isGuest) {
            #$id = base64_decode($id);
            $id = Yii::$app->user->identity->id;
            if($model = User::findOne($id)){
                $model->scenario = User::SCENARIO_REGISTER4;
                if($model->load(Yii::$app->request->post())){
                    #echo "<pre>"; print_r($model->scenario);exit;
                    $vFamilyProperty_ARRAY = Yii::$app->request->post('User');

                    if(is_array($vFamilyProperty_ARRAY['vFamilyProperty'])){
                        $model->vFamilyProperty = implode(",",$vFamilyProperty_ARRAY['vFamilyProperty']);
                    }
                    else {
                        $model->vFamilyProperty = '';
                    }

                    $model->completed_step = $model->setCompletedStep('5');
                    if($model->save()){
                        $this->redirect(['site/about-yourself']);
                    }
                }
                return $this->render('register4',[
                    'model' => $model
                ]);

            }else{
                return $this->redirect(Yii::getAlias('@web'));
            }
        }else{
            return $this->redirect(Yii::getAlias('@web'));
        }

    }
    public function actionAboutYourself($id='')
    {
        if (!Yii::$app->user->isGuest) {
            #$id = base64_decode($id);
            $id = Yii::$app->user->identity->id;
            if($model = User::findOne($id)){
                $tYourSelf_old = $model->tYourSelf;
                $model->scenario = User::SCENARIO_REGISTER5;
                if($model->load(Yii::$app->request->post())){
                    $model->completed_step = $model->setCompletedStep('6');
                    if (strcmp($tYourSelf_old, $model->tYourSelf) !== 0) {
                        $model->eStatusInOwnWord = date('Y-m-d');
                    }
                    if($model->save()){
                        $this->redirect(['site/my-photos']);
                    }
                }
                return $this->render('register5',[
                    'model' => $model
                ]);
            }else{
                return $this->redirect(Yii::getAlias('@web'));
            }
        }else{
            return $this->redirect(Yii::getAlias('@web'));
        }

    }
    public function actionMyPhotos($id='')
    {
        if (!Yii::$app->user->isGuest) {
            #$id = base64_decode($id);
            $id = Yii::$app->user->identity->id;
            #   $id = base64_decode($id);
            if($model = User::findOne($id)){

                $model->scenario = User::SCENARIO_REGISTER6;
                $target_dir = Yii::getAlias('@web').'/uploads/';
                if(Yii::$app->request->post()){
                    if($model->pin_email_vaerification ==''){}
                    $PIN = (rand(1000,9999));
                    $model->pin_email_vaerification = $PIN;
                    $model->completed_step = $model->setCompletedStep('7');
                    if($model->save($model)){
                        $to      = $model->email;
                        $subject = 'Email Verification PIN';
                        $message = "";
                        #$message .= 'hi '."\r\n";
                        $message .= "Dear ".$model->First_Name.",
                        You've entered ".$to." as the email address for your Kande-pohe.com Account. To start using Shaadi profile,
                        Here is 4 digit PIN : ".$PIN.". Please enter this on site for further proceed.
                        ";
                        // $headers = 'From: no-replay@vcodertechnolab.com' . "\r\n" .'Reply-To: webmaster@example.com' . "\r\n" .'X-Mailer: PHP/' . phpversion();
                        #echo $message;exit;
                        #mail($to, $subject, $message, $headers);
                        $res = Yii::$app->mailer->compose()
                            ->setTo($to)
                            ->setFrom('no-replay@vcodertechnolab.com')
                            ->setSubject($subject)
                            ->setTextBody($message)
                            ->send();
                        $this->redirect(['site/verification']);
                    }
                }
                if($model->propic !='')
                    $model->propic = $target_dir.$model->propic;

                #echo "<pre>";print_r($model);
                return $this->render('register6',[
                    'model' => $model
                ]);


            }else{
                return $this->redirect(Yii::getAlias('@web'));
            }

        }else{
            return $this->redirect(Yii::getAlias('@web'));
        }

    }


    public function actionPhotoupload($id){
        $id = base64_decode($id);

        if($model = User::findOne($id)){
            #echo "<pre>"; print_r(":hiiii");exit;

            $model->scenario = User::SCENARIO_REGISTER6;
            #if($model->load(Yii::$app->request->post())){

            $target_dir = Yii::getAlias('@frontend') .'/web/uploads/';
            $target_file = $target_dir . basename($_FILES["fileInput"]["name"]);
            if ($_FILES['fileInput']['name'] !='') {
                // Start
                #$PHOTO_NAME = $generalfuncobj->photoUpload($iUserId,$_FILES['vPhotoCard'],$PATH,$URL,$VISITING_CARD_SIZE_ARRAY,'');
                $CM_HELPER = new CommonHelper();
                $PATH = $CM_HELPER->getUserUploadFolder(1) . "/" . $id . "/";
                $URL = $CM_HELPER->getUserUploadFolder(2) . "/" . $id . "/";
                $USER_SIZE_ARRAY = $CM_HELPER->getUserResizeRatio();
                $OLD_PHOTO = $model->propic;
                $PHOTO_ARRAY = CommonHelper::photoUpload($id, $_FILES['fileInput'], $PATH, $URL, $USER_SIZE_ARRAY, $OLD_PHOTO);
                #echo "<pre>"; print_r($PHOTO_ARRAY);
                #exit;
                // END
                $model->propic = $PHOTO_ARRAY['PHOTO'];//$_FILES['fileInput']['name'];
                $model->eStatusPhotoModify = 'Pending';
                /*if (move_uploaded_file($_FILES["fileInput"]["tmp_name"], $target_file)) {

                    echo "The file ". basename( $_FILES["fileInput"]["name"]). " has been uploaded.";
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }*/
                //var_dump(fileInput);
                if ($model->save()) {
                }
            }
            #}

        }else{
            return $this->redirect(Yii::getAlias('@web'));
        }
    }

    public function actionVerification($id='',$msg='')
    {
        if (!Yii::$app->user->isGuest) {
            #$id = base64_decode($id);
            $id = Yii::$app->user->identity->id;
            #$id = base64_decode($id);
            if($model = User::findOne($id)){
                $model->scenario = User::SCENARIO_REGISTER7;

                if($model->load(Yii::$app->request->post())){
                    #echo "<pre>";print_r(Yii::$app->request->post());echo "</pre>";
                    $USERARRAY = Yii::$app->request->post('User');
                    $PIN = $USERARRAY['email_pin'];
                    if($PIN !=''){
                        if($model->pin_email_vaerification == $PIN){
                            $model->eEmailVerifiedStatus = 'Yes';
                            $model->completed_step = $model->setCompletedStep('8');
                            $model->save($model);
                            $this->redirect(['user/dashboard','id'=>base64_encode($id)]);
                        }else{
                            $model->email_verification_msg = 'Incorrect PIN. Please Enter Valid PIN.';
                            $model->error_class = 'error';
                            return $this->render('register7',[
                                'model' => $model
                            ]);
                            //Oops! Please ensure all fields are valid
                        }
                    }

                }

                if($msg !='')
                {$model->email_verification_msg = $msg;
                    $model->error_class = 'success';
                }
                return $this->render('register7',[
                    'model' => $model
                ]);

            }else{
                return $this->redirect(Yii::getAlias('@web'));
            }
        }else{
            return $this->redirect(Yii::getAlias('@web'));
        }


    }
    public function actionResendemailpin($id='')
    {
        if (!Yii::$app->user->isGuest) {
            #$id = base64_decode($id);
            $id = Yii::$app->user->identity->id;
            #$id = base64_decode($id);
            if($model = User::findOne($id)){
                #if($model->eFirstVerificationMailStatus == 'YES' ){
                    $model->scenario = User::SCENARIO_REGISTER6;

                    $target_dir = Yii::getAlias('@web').'/uploads/';
                    #if(Yii::$app->request->post()){
                    $PIN = (rand(1000,9999));
                    $model->pin_email_vaerification = $PIN;
                    if($model->save($model)){
                        $to      = $model->email;
                        $subject = 'Email Verification PIN';
                        $message = "";
                        #$message .= 'hi '."\r\n";
                        $message .= "Dear ".$model->First_Name.",
                        You've entered ".$to." as the email address for your Kande-pohe.com Account. To start using Shaadi profile,
                        Here is 4 digit PIN : ".$PIN.". Please enter this on site for further proceed.
                        ";
                        $headers = 'From: no-replay@vcodertechnolab.com' . "\r\n" .'Reply-To: webmaster@example.com' . "\r\n" .'X-Mailer: PHP/' . phpversion();
                        #echo $message;exit;
                        mail($to, $subject, $message, $headers);
                        #$model->email_verification_msg = ' Please Check Your Mail for new PIN.';
                        $this->redirect(['site/verification','msg'=>'New PIN has been sent on your Email.']);

                    }
                    #}
                    return $this->render('register7',[
                        'model' => $model
                    ]);
                /*}else{
                    return $this->redirect(Yii::getAlias('@web'));
                }*/

            }else{
                return $this->redirect(Yii::getAlias('@web'));
            }
        }else{
            return $this->redirect(Yii::getAlias('@web'));
        }

    }
    
}
