<?php
/**
 * Created by JetBrains PhpStorm.
 * User: oleg
 * Date: 27.05.16
 * Time: 15:33
 * To change this template use File | Settings | File Templates.
 */
namespace backend\modules\profile\models;

use Yii;
use backend\modules\profile\models\ProfileField;
use yii\db\ActiveQuery;

class ProfileFieldQuery extends ActiveQuery
{
    public function forAll ()
    {
        return $this->andWhere(['section'=>1, 'visible' => ProfileField::VISIBLE_ALL]);
    }
    public function forUser ()
    {
        return $this->andWhere(['section'=>1, 'visible' => [ProfileField::VISIBLE_REGISTER_USER, ProfileField::VISIBLE_ALL]]);
    }
    public function forOwner ()
    {
        return $this->andWhere(['section'=>1, 'visible' => [ProfileField::VISIBLE_REGISTER_USER, ProfileField::VISIBLE_ALL, ProfileField::VISIBLE_ONLY_OWNER]]);
    }
    public function forRegistration ()
    {
        return $this->andWhere(['section'=>1, 'required' => ProfileField::REQUIRED_YES_SHOW_REG]);
    }
    public function forAdmin ()
    {
        return $this->andWhere(['section'=>1, 'visible' => [ProfileField::VISIBLE_REGISTER_USER, ProfileField::VISIBLE_ALL, ProfileField::VISIBLE_ONLY_OWNER], 'required' => ProfileField::REQUIRED_YES_SHOW_REG]);
    }
}
