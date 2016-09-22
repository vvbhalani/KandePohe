<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\MasterMaritalStatus */

$this->title = 'Create Marital Status';
$this->params['breadcrumbs'][] = ['label' => 'Marital Status', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary">
            <div class="box-header with-border">
              <!--<h3 class="box-title">Quick Example</h3>-->
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            
            <div class="box-body">
				<div class="master-marital-status-create">

				    <!--<h1><?= Html::encode($this->title) ?></h1>-->

				    <?= $this->render('_form', [
				        'model' => $model,
				    ]) ?>

				</div>
			</div>
</div>
