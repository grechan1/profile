<?php

namespace backend\modules\profile\models;

use Yii;
use backend\modules\profile\models\ProfileFieldQuery;

/**
 * This is the model class for table "profile_field".
 *
 * @property integer $id
 * @property string $varname
 * @property string $title
 * @property string $field_type
 * @property string $field_size
 * @property string $field_size_min
 * @property integer $required
 * @property string $match
 * @property string $range
 * @property string $error_message
 * @property string $other_validator
 * @property string $default
 * @property string $widget
 * @property string $widgetparams
 * @property integer $position
 * @property integer $visible
 */
class ProfileField extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    const VISIBLE_ALL=3;
    const VISIBLE_REGISTER_USER=2;
    const VISIBLE_ONLY_OWNER=1;
    const VISIBLE_NO=0;

    const REQUIRED_NO = 0;
    const REQUIRED_YES_SHOW_REG = 1;
    const REQUIRED_NO_SHOW_REG = 2;
    const REQUIRED_YES_NOT_SHOW_REG = 3;

    const CHANGE_GUEST = 0;
    const CHANGE_USER = 1;
    const CHANGE_MODERATOR = 2;
    const CHANGE_ADMIN = 3;

    public static function tableName()
    {
        return 'profile_field';
    }
    public function transactions(){
        return [
          'create' => self::OP_INSERT,
          'api' => self::OP_INSERT | self::OP_UPDATE | self::OP_DELETE,
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['varname', 'title', 'field_type'], 'required'],
            [['required', 'position', 'visible', 'change'], 'integer'],
            ['varname', 'match', 'pattern' => '/^[A-Za-z_0-9]+$/u','message' => "Variable name may consist of A-z, 0-9, underscores, begin with a letter."],
            ['varname', 'unique', 'message' => "This field already exists."],
            [['varname', 'field_type'], 'string', 'max' => 50],
            [['title', 'match', 'range', 'error_message', 'default', 'widget'], 'string', 'max' => 255],
            [['field_size', 'field_size_min'], 'string', 'max' => 15],
            [['other_validator', 'widgetparams'], 'string', 'max' => 5000],
            //['change', 'validateChange'],
        ];
    }
    public function validateChange($attribute)
    {
        if ($this->$attribute==3 && Yii::$app->user->id!=1)
            $this->addError($attribute, 'Ви не можете змінити це поле.');
        if ($this->$attribute==2 && Yii::$app->user->isGuest)
            $this->addError($attribute, 'Ви не можете змінити це поле.');
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'varname' => 'Varname',
            'title' => 'Title',
            'field_type' => 'Field Type',
            'field_size' => 'Field Size',
            'field_size_min' => 'Field Size Min',
            'required' => 'Required',
            'match' => 'Match',
            'range' => 'Range',
            'error_message' => 'Error Message',
            'other_validator' => 'Other Validator',
            'default' => 'Default',
            'widget' => 'Widget',
            'widgetparams' => 'Widgetparams',
            'position' => 'Position',
            'visible' => 'Visible',
            'Change' => 'Who can change',
        ];
    }
    public function widgetView($model) {
        if ($this->widget && class_exists($this->widget)) {
            $widgetClass = new $this->widget;

            $arr = $this->widgetparams;
            if ($arr) {
                $newParams = $widgetClass->params;
                $arr = (array)CJavaScript::jsonDecode($arr);
                foreach ($arr as $p=>$v) {
                    if (isset($newParams[$p])) $newParams[$p] = $v;
                }
                $widgetClass->params = $newParams;
            }

            if (method_exists($widgetClass,'viewAttribute')) {
                return $widgetClass->viewAttribute($model,$this);
            }
        }
        return false;
    }

    public function widgetEdit($model,$params=[]) {
        if ($this->widget && class_exists($this->widget)) {
            $widgetClass = new $this->widget;

            $arr = $this->widgetparams;
            if ($arr) {
                $newParams = $widgetClass->params;
                $arr = (array)CJavaScript::jsonDecode($arr);
                foreach ($arr as $p=>$v) {
                    if (isset($newParams[$p])) $newParams[$p] = $v;
                }
                $widgetClass->params = $newParams;
            }

            if (method_exists($widgetClass,'editAttribute')) {
                return $widgetClass->editAttribute($model,$this,$params);
            }
        }
        return false;
    }

    public static function itemAlias($type,$code=NULL) {
        $_items = [
            'field_type' => [
                'INTEGER' => 'INTEGER',
                'VARCHAR' => 'VARCHAR',
                'TEXT'=> 'TEXT',
                'DATE'=> 'DATE',
                'FLOAT'=> 'FLOAT',
                'BOOL'=> 'BOOL',
                'BLOB'=> 'BLOB',
                'BINARY'=> 'BINARY',
            ],
            'required' => [
                self::REQUIRED_NO => 'No',
                self::REQUIRED_NO_SHOW_REG => 'No, but show on registration form',
                self::REQUIRED_YES_SHOW_REG => 'Yes and show on registration form',
                self::REQUIRED_YES_NOT_SHOW_REG => 'Yes',
            ],
            'visible' => [
                self::VISIBLE_ALL => 'For all',
                self::VISIBLE_REGISTER_USER => 'Registered users',
                self::VISIBLE_ONLY_OWNER => 'Only owner',
                self::VISIBLE_NO => 'Hidden',
            ],
            'change' => [
                self::CHANGE_GUEST => 'all',
                self::CHANGE_USER => 'Registered users',
                self::CHANGE_MODERATOR => 'Moderator',
                self::CHANGE_ADMIN => 'Only Administrator',
            ],
        ];
        if (isset($code))
            return isset($_items[$type][$code]) ? $_items[$type][$code] : false;
        else
            return isset($_items[$type]) ? $_items[$type] : false;
    }
    public static function find()
    {
        return new ProfileFieldQuery(get_called_class());
    }
}
