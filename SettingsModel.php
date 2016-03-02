<?php

namespace rafalkot\yii2settings;

use yii\base\DynamicModel;

/**
 * Helper dynamic model
 *
 * @package rafalkot\yii2settings
 */
class SettingsModel extends DynamicModel
{

    /**
     * @inheritdoc
     */
    public function formName()
    {
        return 'Settings';
    }
}