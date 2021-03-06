<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\components\CommonHelper;
use yii\helpers\ArrayHelper;

  echo $this->render('/layouts/parts/_headerregister.php');
?>
<main>
  <div class="container-fluid">
    <div class="row no-gutter bg-dark">
      <div class="col-md-3 col-sm-12">
        <div class="sidebar-nav">
          <div class="navbar navbar-default" role="navigation">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-navbar-collapse"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
              <span class="visible-xs navbar-brand">Sidebar menu</span> </div>
            <div class="navbar-collapse collapse sidebar-navbar-collapse">
              <ul class="nav navbar-nav">
                <li class="step_done"><a href="<?=$HOME_URL_SITE?>basic-details">Basic Details</a></li>
                <li class="step_done"><a href="<?=$HOME_URL_SITE?>education-occupation">Education &amp; Occupation</a></li>
                <li class="step_done"><a href="<?=$HOME_URL_SITE?>life-style">Lifestyle &amp; Appearance</a></li>
                <li class="step_done"><a href="<?=$HOME_URL_SITE?>about-family">Family</a></li>
                <li class="active"><a href="javascript:void();">About Yourself</a></li>
              </ul>
            </div>
            <!--/.nav-collapse -->
          </div>
        </div>
      </div>
      <div class="col-sm-9">
        <div class="right-column"> <span class="welcome-note">
          <p><strong>Welcome <?= $model->First_Name; ?> !</strong> About yourself details will help us find you the best matches</p>
          </span>
          <div class="row no-gutter">
            <div class="col-lg-8 col-md-12 col-sm-12">
              <div class="white-section mrg-tp-20 mrg-bt-10">
                <h3>About YourSelf</h3>
                <!--<span class="error">Oops! Please ensure all fields are valid</span>
                <p><span class="text-danger">*</span> marked fields are mandatory</p>-->
                <?php
                $form = ActiveForm::begin([
                    'id' => 'form-register5',
                ]);
                ?>
                  <div class="box">
                    <div class="small-col">
                      <div class="required1"><!--<span class="text-danger">*</span>--></div>
                    </div>
                    <div class="mid-col">
                      <div class="form-cont">

                        <span class="input input--akira input--filled input-textarea">
                          <!--<input class="input__field input__field--akira" type="text" name="tYourSelf" value="<?/*= ($model->tYourSelf)*/?>">-->


                          <textarea class="input__field input__field--akira" cols="50" rows="5" name="User[tYourSelf]" ><?= ($model->tYourSelf)?></textarea>
                          <label class="input__label input__label--akira" for="input-22">
                            <span class="input__label-content input__label-content--akira">Describe yourself in your own words</span> </label>
                        </span>
                      </div>
                    </div>
                    <div class="small-col tp ">
                      <a href="#" data-toggle="tooltip" data-placement="right" title="Mention Your Describe yourself in your own words"><?= Html::img('@web/images/tooltip.jpg', ['width' => '21','height' => 21,'alt' => 'help']); ?></a>
                    </div>
                  </div>
                <div class="row">
                  <div class="col-sm-10 col-sm-offset-1">
                    <label for="Remember" class="control-label">Family Affluence Level :</label>
                  </div>
                </div>
                  <div class="box">
                    <div class="small-col">
                      <div class="required1"><!--<span class="text-danger">*</span>--></div>
                    </div>
                    <div class="mid-col">
                      <div class="form-cont">
                        <div class="radio dl" id="IVA">
                          <dt></dt>
                          <dd>
                            <?= $form->field($model, 'vDisability')->RadioList(
                                ['None'=>'None','Physical Disability'=>'Physical Disability'],
                                [
                                    'item' => function($index, $label, $name, $checked, $value) {
                                      $checked = ($checked) ? 'checked' : '';
                                      $return = '<input type="radio" id="' . $value . '" name="' . $name . '" value="' . $value . '" ' . $checked . '>';
                                      $return .= '<label for="'.$value.'">' . ucwords($label) . '</label>';
                                      return $return;
                                    }

                                ]
                            )->label(false);?>
                          </dd>
                          <!--<dd>
                            <input id="None" type="radio" name="disability" value="None" <?/*= ($model->disability == 'None') ? 'checked="checked"' :''; */?>>
                            <label for="None">None</label>
                            <input id="Physical_Disability" type="radio" name="disability" value="Physical Disability" <?/*= ($model->disability == 'Physical Disability') ? 'checked="checked"' :''; */?>>
                            <label for="Physical_Disability">Physical Disability</label>
                          </dd>-->
                        </div>
                      </div>
                    </div>
                    <div class="small-col tp ">
                      <a href="#" data-toggle="tooltip" data-placement="right" title="Mention Your Family Affluence Level"><?= Html::img('@web/images/tooltip.jpg', ['width' => '21','height' => 21,'alt' => 'help']); ?></a>
                    </div>
                  </div>
                <div class="row">
                  <div class="col-sm-10 col-sm-offset-2">
                    <?= Html::submitButton('create my profile', ['class' => 'btn btn-primary mrg-tp-10 col-xs-5 col-xs-5 pull-left', 'name' => 'register5']) ?>
                    <!--<a href="<?/*=$HOME_URL_SITE*/?>about-yourself" class="btn btn-primary mrg-tp-10 col-xs-5 col-xs-5 pull-right">Skip</a>-->
                  </div>
                </div>
                <?php ActiveForm::end(); ?>
              </div>
            </div>
          <?php
              echo $this->render('/layouts/parts/_rightbarregister.php');
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
