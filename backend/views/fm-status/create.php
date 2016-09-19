<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\MasterFmStatus */

$this->title = 'Create Father-Mother Status';
$this->params['breadcrumbs'][] = ['label' => 'Father-Mother Statuses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary">
            <div class="box-header with-border">
              <!--<h3 class="box-title">Quick Example</h3>-->
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form">
            <div class="box-body">
				<div class="master-fm-status-create">

				    <!--<h1><?= Html::encode($this->title) ?></h1>-->

				    <?= $this->render('_form', [
				        'model' => $model,
				    ]) ?>

				</div>
			</div>
</div>