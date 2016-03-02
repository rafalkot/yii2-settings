<?php

namespace rafalkot\yii2settings;

use yii\base\Exception;
use yii\base\Widget;

/**
 * Settings form widget class.
 *
 * @package rafalkot\yii2settings
 */
class SettingsForm extends Widget
{

    /**
     * @var SettingsTrait Configured object
     */
    public $object;

    /**
     * @var SettingsModel Helper dynamic model
     */
    private $_model;

    /**
     * @var array Configuration array
     */
    private $_formConfig = [];


    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $config = $this->object->getSettingsFormConfig();

        $this->_model = new SettingsModel(array_keys($config));
        $this->_model->setAttributes($this->object->getSetting(), false);

        foreach ($config as $key => $element) {
            $element['input'] = isset($element['input']) ? $element['input'] : 'text';
            if (in_array($element['input'], ['dropdown', 'checkboxList']) && empty($element['options'])
            ) {
                throw new Exception('Input type ' . $element['input'] . ' requires `options` property');
            }

            if (!$this->_model->{$key} && isset($element['default'])) {
                $this->_model->{$key} = $element['default'];
            }

            if (!isset($element['label'])) {
                $config[$key]['label'] = $this->_model->generateAttributeLabel($key);
            }
        }

        $this->_formConfig = $config;
    }


    /**
     * @inheritdoc
     */
    public function run()
    {
        $settings = \Yii::$app->request->post('Settings', []);

        if (!empty($settings)) {
            $this->_model->setAttributes($settings, false);
            $this->object->setSetting($this->_model->getAttributes());

            \Yii::$app->session->setFlash('settings', \Yii::t('yii2settings', 'Settings have been saved'));
        }

        if (\Yii::$app->request->isAjax) {
            return $this->getView()->renderAjax('form', [
                'model' => $this->_model,
                'elements' => $this->_formConfig
            ], $this);
        }

        return $this->render('form', [
            'model' => $this->_model,
            'elements' => $this->_formConfig
        ]);
    }
}