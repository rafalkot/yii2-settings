<?php

namespace rafalkot\yii2settings;

use yii\base\Exception;

/**
 * Class SettingsTrait
 *
 * Provides an easy access to settings stored in DB.
 * Remember to implement SettingsInterface too.
 *
 * @package rafalkot\yii2settings
 */
trait SettingsTrait
{

    /**
     * @return string Category name
     */
    abstract public function getSettingsCategory();

    /**
     * @return Settings Settings component
     */
    public function getSettingsComponent()
    {
        return \Yii::$app->settings;
    }

    /**
     * Returns settings from current category.
     *
     * @param string|array $key Single or multiple keys in array
     * @param mixed $default Default value when setting does not exist
     * @return mixed Setting value
     * @throws Exception
     */
    public function getSetting($key = null, $default = null)
    {
        return $this->getSettingsComponent()->get($this->getSettingsCategory(), $key, $default);
    }

    /**
     * Saves setting
     *
     * @param mixed $key Setting key or array of settings ie.: ['key' => value'', 'key2' => 'value2']
     * @param mixed $value Setting value
     * @throws Exception
     */
    public function setSetting($key, $value = null)
    {
        $this->getSettingsComponent()->set($this->getSettingsCategory(), $key, $value);
    }

    /**
     * Removes setting
     *
     * @param array|string|null $key Setting key, keys array or null to delete all settings from category
     * @throws Exception
     */
    public function removeSetting($key = null)
    {
        $this->getSettingsComponent()->remove($this->getSettingsCategory(), $key);
    }

    /**
     * @return array Settings form config.
     */
    public function getSettingsFormConfig()
    {
        return [];
    }
}