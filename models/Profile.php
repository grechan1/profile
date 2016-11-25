<?php

namespace backend\modules\profile\models;

use Yii;

/**
 * This is the model class for table "profile".
 *
 * @property integer $user_id
 * @property string $lastname
 * @property string $firstname
 * @property double $balance
 */
class Profile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    private $_model;
    public $model_reg=false;

    public static function tableName()
    {
        return 'profile';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {


        $required = [];
        $numerical = [];
        $integer = [];
        $rules = [];

        $model=$this->getFields();

        foreach ($model as $field) {
            if ($field->required==ProfileField::REQUIRED_YES_NOT_SHOW_REG||$field->required==ProfileField::REQUIRED_YES_SHOW_REG)
                array_push($required,$field->varname);
            if ($field->field_type=='FLOAT')
                array_push($numerical,$field->varname);
            if ($field->field_type=='INTEGER')
                array_push($integer,$field->varname);
            if ($field->field_type=='VARCHAR'||$field->field_type=='TEXT') {
                $field_rule = [[$field->varname], 'string', 'max'=>$field->field_size, 'min' => $field->field_size_min];
                if ($field->error_message) $field_rule['message'] = $field->error_message;
                array_push($rules,$field_rule);
            }
            if ($field->other_validator) {
                if (strpos($field->other_validator,'[')===0) {
                    $validator = (array)CJavaScript::jsonDecode($field->other_validator);
                    foreach ($validator as $name=>$val) {
                        $field_rule = [$field->varname, $name];
                        $field_rule = array_merge($field_rule,(array)$validator[$name]);
                        if ($field->error_message) $field_rule['message'] = $field->error_message;
                        array_push($rules,$field_rule);
                    }
                } else {
                    $field_rule = [$field->varname, $field->other_validator];
                    if ($field->error_message) $field_rule['message'] = $field->error_message;
                    array_push($rules,$field_rule);
                }
            } elseif ($field->field_type=='DATE') {
                $field_rule = [$field->varname, 'date', 'format' => 'yyyy-mm-dd'];
                if ($field->error_message) $field_rule['message'] = $field->error_message;
                array_push($rules,$field_rule);
            }
            if ($field->match) {
                $field_rule = [$field->varname, 'match', 'pattern' => $field->match];
                if ($field->error_message) $field_rule['message'] = $field->error_message;
                array_push($rules,$field_rule);
            }
            if ($field->range) {
                $field_rule = [$field->varname, 'in', 'range' => self::rangeRules($field->range)];
                if ($field->error_message) $field_rule['message'] = $field->error_message;
                array_push($rules,$field_rule);
            }
        }
        array_push($required,'user_id');
        array_push($integer,'user_id');
        array_push($rules,[implode(',',$required), 'required']);
        array_push($rules,[implode(',',$numerical), 'number']);
        array_push($rules,[implode(',',$integer), 'integer']);
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $labels = [
            'user_id' => 'User ID',
        ];
        $model=$this->getFields();

        foreach ($model as $field)
            $labels[$field->varname] = $field->title;

        return $labels;
    }
    public function attributeProperties()
    {
        $model=$this->getFields();

        foreach ($model as $field)
            $properties[$field->varname] = ['field_type'=>$field->field_type, 'field_size'=>$field->field_size, 'field_size_min'=>$field->field_size_min, 'change'=>$field->change];

        return $properties;
    }
    public function getFields() {
        if ($this->model_reg){
            if (!$this->_model)
                $this->_model=ProfileField::findProfileUser()->forRegistration()->all();
        } else {
            if (Yii::$app->user->isGuest)
                if (!$this->_model)
                    $this->_model=ProfileField::findProfileUser()->forAll()->all();
            if (!Yii::$app->user->isGuest)
                if (!$this->_model)
                    $this->_model=ProfileField::findProfileUser()->forUser()->all();
            if (Yii::$app->user->id==1)
                if (!$this->_model)
                    $this->_model=ProfileField::findProfileUser()->forAdmin()->all();
        }
        return $this->_model;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $attributes=$this->attributeLabels();
            $properties=$this->attributeProperties();
            foreach($attributes as $attribute=>$value){
                if ($attribute!='user_id'){
                $property=$properties[$attribute];
                if ($property['change']==3 && Yii::$app->user->id!=1)
                    return false;
                elseif ($property['change']==2 && Yii::$app->user->isGuest)
                    return false;
                else
                    return true;
                }
            }
        } else {
            return false;
        }
    }
}
