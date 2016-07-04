<?php

namespace app\components;

use app\models\Bookmark;
use app\models\User;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

class MyHelper extends Html
{
    /**
     * @param $userId
     * @param $advertId
     * @return mixed
     */
    public static function contacts($userId, $advertId)
    {
        $user = User::findOne(['id' =>$userId]);
        $notSet = '<span class="not-set">not set</span>';

        if (Yii::$app->user->isGuest || $userId !== Yii::$app->user->identity->getId()) {
            $contacts['contact'] = '<p class="contact">Contact the author: ' . $user->getFullName() . '</p>';
            $contacts['email'] = '<p><a href="' . Url::toRoute(['site/contact-author', 'id' => $advertId]) . '">
                        <strong>Write an e-mail</strong></a></p>';
            $contacts['phone'] = empty($user->phone) ? '' : '<p><strong>Phone: </strong>' . $user->phone . '</p>';
            $contacts['skype'] = empty($user->skype) ? '' : '<p><strong>Skype: </strong>' . $user->skype . '</p>';
        } else {
            $contacts['contact'] = '<p class="contact">My contacts: </p>';
            $contacts['email'] = '<p><strong>E-mail: </strong>' . $user->email . '</p>';
            $contacts['phone'] = empty($user->phone) ? $notSet : '<p><strong>Phone: </strong>' . $user->phone . '</p>';
            $contacts['skype'] = empty($user->skype) ? $notSet : '<p><strong>Skype: </strong>' . $user->skype . '</p>';
        }
        
        return $contacts;
    }

    /**
     * Creates buttons to control and advert depending on its author
     * 
     * @param $userId
     * @param $advertId
     * @return array
     */
    public static function buttons($userId, $advertId)
    {
        $buttons = [
            'addToBookmarks' => '',
            'update' => '',
            'delete' => '',
        ];

        $url = Url::toRoute(['bookmark/add-to-bookmarks', 'id' => $advertId]);

        if (!Yii::$app->user->isGuest) {
            $isInBookmarks = Bookmark::find()->where([
                'user_id' => Yii::$app->user->identity->getId(), 'advert_id' => $advertId
            ])->all();

            if (!empty($isInBookmarks)) {
                $toBookmarks = 'Delete ' . 'from bookmarks';
            } else {
                $toBookmarks = 'Add to bookmarks';
            }

            $buttons['addToBookmarks'] = Html::input('submit', 'button', $toBookmarks,
                [
                    'id' => 'book',
                    'class' => 'btn btn-primary',
                    'onclick' => '
                        $.ajax({
                        url: "' . $url . '",
                        success: function ( data ) {
                            $( "#book" ).html( data ).attr("value", data );
                        }
                        })
                    '
                ]);
        }
        
        if ($userId == Yii::$app->user->identity->getId()) {
            $buttons['update'] = Html::a('Update advert', ['update', 'id' => $advertId], [
                'class' => 'btn btn-primary'
            ]);
            $buttons['delete'] = Html::a('Delete advert', ['delete', 'id' => $advertId], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this advert?',
                    'method' => 'post',
                ],
            ]);
        }
        
        return $buttons;
    }
}