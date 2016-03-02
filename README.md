# Yii2-settings
Settings management component for Yii2 Framework.

## Features

* settings stored in DB
* simple API for read/write/remove
* categorized settings
* easy integration by SettingsTrait with your components, models, modules etc.
* SettingsForm widget

## Installation
### 1. Install via composer
Add this line to **require** section of your **composer.json**

```
 "rafalkot/yii2-settings": "*"
```

**Update composer** by running command

```
$ php composer.phar update
```

### 2. Add component to your app config

Add yii2-settings component to your configuration files

```php
'components' => [
	...
	'settings' => [
		'class' => 'rafalkot\yii2settings\Setting',
		// optional configuration:
		'db' => 'db', // DB Component ID
		'preLoad' => ['category1', 'category2'] // Categories to be loaded on component initialization
	],
	...
]
```

### 3. Create DB table

```sql
CREATE TABLE IF NOT EXISTS `setting` (
  `category` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'system',
  `key` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `setting`
 ADD PRIMARY KEY (`category`,`key`), ADD KEY `fk_setting_user1_idx` (`created_by`), ADD KEY `fk_setting_user2_idx` (`updated_by`);
```

## Usage
### Component

Reading settings:

```php
// will return `key` setting value from `categoryName` category or `defaultValue` (defaults sets to be null)
Yii::$app->settings->get('categoryName', 'key', 'defaultValue'); 

// will return an array of `key1` & `key2` settings from `categoryName` category
Yii::$app->settings->get('categoryName', ['key1', 'key2']); 

// you can set default values too
Yii::$app->settings->get('categoryName', ['key1', 'key2'], ['key1' => 'key1default', 'key2' => 'key2default']); 

// will return array of all settings from `categoryName` category 
Yii::$app->settings->get('categoryName');
```

Saving settings:

```php
// saves single setting
Yii::$app->settings->set('categoryName', 'key', 'value');

// saves multiple settings
Yii::$app->settings->set('categoryName', [
	'key1' => 'value 1',
	'key2' => 'value 2'
]);
```

Removing settings:

```php
// removes single setting
Yii::$app->settings->remove('categoryName', 'key');

// removes multiple settings
Yii::$app->settings->remove('categoryName', ['key1', 'key2']);

// removes all settings from category
Yii::$app->settings->remove('categoryName');
```

Loading settings:

```php
// loads settings from single category
Yii::$app->settings->load('categoryName');

// loads settings from multiple categories
Yii::$app->settings->load(['categoryName1', 'categoryName2']);
```

### SettingsTrait
`SettingsTrait` provides few methods to simple settings access.

Example usage (all operations are using `site` category):

```php
use rafalkot\yii2settings\SettingsTrait;

class Site
{
	use SettingsTrait;
	
	public function getSettingsCategoryName()
	{
		return 'site';
	}
	
	public function someMethod()
	{
		$this->setSetting('key', 'value');
		$this->getSetting('key', 'defaultValue');
		$this->getSetting();
		$this->removeSetting('key');
		$this->removeSetting();
	}	
}
```

### SettingsForm widget

`SettingsForm` widget renders form based on our form definition.

It could be done by overriding `getSettingsFormConfig` method from `SettingsTrait`.

Firstly, let's add form definition to our class:

```php
use rafalkot\yii2settings\SettingsTrait;
use yii\jui\DatePicker;

class Site
{
	use SettingsTrait;
	
	public function getSettingsCategoryName()
	{
		return 'site';
	}

	public function getSettingsFormConfig()
	{
		return [
			// text input
			'title' => [
				'input' => 'text',
				'label' => 'Site Title'
			],
			// dropdown list
			'comments' => [
				'input' => 'dropdown',
				'label' => 'Are comments enabled?',
				'options' => [1 => 'Yes', 0 => 'No'],
				'default' => 1
			],
			// checkboxes
			'languages' => [
				'input' => 'checkboxList',
				'options' => ['en' => 'English', 'pl' => 'Polish']
			],
			// custom input
			'custom_input' => [
				'input' => function($model, $key) {
	                 return DatePicker::widget(['model' => $model, 'attribute' => $key);
	       		},
	          	'label' => 'Some label'
			]
		];
	}
}
```

Sample action

```php
class SettingsController extends yii\web\Controller
{
	public function actionSite()
	{
		$site = new Site();
		
		$this->render('site', [
			'site' => $site
		]);
	}
}
```

Widget's usage in view

```php
use rafalkot\yii2settings\SettingsForm;

echo SettingsForm::widget([
	'object' => $site
]);
```

## License 
Yii2-settings is released under the MIT license, see LICENSE file for details.