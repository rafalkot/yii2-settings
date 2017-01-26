<?php
use rafalkot\yii2settings\SettingsModel;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Alert;
use yii\bootstrap\Button;


/**
 * @var $elements []
 * @var $model SettingsModel
 */

$form = ActiveForm::begin();
$message = \Yii::$app->session->getFlash('settings');
if ($message) {
    echo Alert::widget([
        'options' => [
            'class' => 'alert-success',
        ],
        'body' => $message
    ]);
}
foreach ($elements as $key => $element) {
    if (is_callable($element['input'])) {
        echo $element['input']($model, $key);
    } else {
        switch ($element['input']) {
            case 'dropdown':
                echo $form->field($model, $key)->dropDownList($element['options'])->label($element['label']);
                break;
            case 'checkboxList':
                echo $form->field($model, $key)->checkboxList($element['options'])->label($element['label']);
                break;
            default:
            case 'text':
                echo $form->field($model, $key)->label($element['label']);
        }
    }
}

echo '<div class="form-group">';
echo Button::widget([
    'label' => Yii::t('yii2settings', 'Save'),
    'options' => ['class' => 'btn-primary']
]);
echo '</div>';

$form->end();
