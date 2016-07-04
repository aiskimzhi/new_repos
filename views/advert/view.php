<?php

use app\components\MyHelper;
use app\models\Advert;

/* @var $model app\models\Advert */
/* @var $gallery */
/* @var $pictures app\models\Pictures */
/* @var $upload app\models\UploadForm */

$this->title = $model->title;
?>
<div class="advert-view">

    <div class="category"><?= $model->category->name ?> » <?= $model->subcategory->name ?></div>

    <div class="title"><?= $model->title ?></div>

    <div class="region"><?= $model->region->name ?>, <?= $model->city->name ?></div>

    <div class="date-update">
        Last update: <?= date(Yii::$app->params['dateFormat'], $model->updated_at) ?>
    </div>

<?php
   echo $this->render($gallery, [
       'pictures' => $pictures,
       'model' => $model,
       'upload' => $upload,
   ]);
?>

    <div class="advert-text">
        <?= $model->text ?>

        <br><br>
        
        <div class="advert-title">
            <strong>Price: </strong><?= Advert::countPrice($model->id) ?>
            <?= strtoupper(Yii::$app->user->isGuest ? 'usd' : Yii::$app->user->identity->getCurrency()) ?>
        </div>
    </div>

    <div class="contacts">
        <?php foreach (MyHelper::contacts($model->user_id, $model->id) as $contact) : ?>
            <?= $contact ?>
        <?php endforeach; ?>
    </div>

    <div class="advert-buttons">
         <?php foreach (MyHelper::buttons($model->user_id, $model->id) as $button) : ?>
            <?= $button ?>
        <?php endforeach;; ?>
    </div>

</div>


