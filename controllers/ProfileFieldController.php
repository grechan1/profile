<?php

namespace backend\modules\profile\controllers;

use Yii;
use backend\modules\profile\models\ProfileField;
use backend\modules\profile\models\Profile;
use backend\modules\product\models\ProductProfile;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\db\Exception;

/**
 * ProfileFieldController implements the CRUD actions for ProfileField model.
 */
class ProfileFieldController extends Controller
{
    /**
     * @inheritdoc
     */

    private static $_widgets = array();

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all ProfileField models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => ProfileField::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ProfileField model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    /**
     * Creates a new ProfileField model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ProfileField();

        //$fieldTypeList=ArrayHelper::map($model->profile_filed_type, 'id', 'name');
        //$scheme=Yii::$app->db->schema;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            //$model->field_type=$this->fieldType($model->field_type);
            if ($model->section==1)
                $profile=new Profile();
            if ($model->section==2)
                $profile=new ProductProfile();
            $sql = 'ALTER TABLE '.$profile->tableName().' ADD `'.$model->varname.'` ';
            $sql .= $model->field_type;
            if (
                $model->field_type!='TEXT'
                && $model->field_type!='DATE'
                && $model->field_type!='BOOL'
                && $model->field_type!='BLOB'
                && $model->field_type!='BINARY'
            )
                $sql .= '('.$model->field_size.')';
            $sql .= ' NOT NULL ';

            //if ($model->field_type!='TEXT'&&$model->field_type!='BLOB'||$scheme!='CMysqlSchema') {
            if ($model->field_type!='TEXT'&&$model->field_type!='BLOB') {
                if ($model->default)
                    $sql .= " DEFAULT '".$model->default."'";
                else
                    $sql .= ((
                        $model->field_type=='TEXT'
                            ||$model->field_type=='VARCHAR'
                            ||$model->field_type=='BLOB'
                            ||$model->field_type=='BINARY'
                    )?" ":(($model->field_type=='DATE')?" DEFAULT '0000-00-00'":" DEFAULT 0"));
            }
            /*return $this->render('create', [
                'model' => $model,
                'sql'=>$sql
            ]);*/
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if (Yii::$app->db->createCommand($sql)->execute()==0)
                    throw new Exception('Command cannot be executed.');
                if (!$model->save())
                    throw new Exception('Model cannot be saved.');
                $transaction->commit();
                $this->redirect(array('view','id'=>$model->id));
            } catch (Exception $e) {
                $transaction->rollBack();
                throw new Exception ("catch transaction, ".$e->getMessage());
            }
        } else {
            //$this->registerScript();
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ProfileField model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ProfileField model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model=$this->findModel($id);
        $profile=new Profile();
        $sql = 'ALTER TABLE '.$profile->tableName().' DROP `'.$model->varname.'` ';
        try {
            if (Yii::$app->db->createCommand($sql)->execute()==0)
                throw new Exception('Command cannot be executed.');
            if (!$model->delete())
                throw new Exception('Model cannot be saved.');
            return $this->redirect(['index']);
        } catch (Exception $e) {
            throw new Exception ("catch Exception, ".$e->getMessage());
        }
    }

    /**
     * Finds the ProfileField model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProfileField the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProfileField::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    public function fieldType($type) {
        $profile_field=new ProfileField;
        $field_type_arr = $profile_field->profile_filed_type;
        $type=$field_type_arr[$type];
        return $type;
    }

    public static function getWidgets($fieldType='') {
        $basePath=Yii::getAlias('app.modules.profile.components');
        $widgets = [];
        $list = [''=>'No'];
        if (self::$_widgets) {
            $widgets = self::$_widgets;
        } else {
            $d = dir($basePath);
            while (false !== ($file = $d->read())) {
                if (strpos($file,'UW')===0) {
                    list($className) = explode('.',$file);
                    if (class_exists($className)) {
                        $widgetClass = new $className;
                        if ($widgetClass->init()) {
                            $widgets[$className] = $widgetClass->init();
                            if ($fieldType) {
                                if (in_array($fieldType,$widgets[$className]['fieldType'])) $list[$className] = $widgets[$className]['label'];
                            } else {
                                $list[$className] = $widgets[$className]['label'];
                            }
                        }
                    }
                }
            }
            $d->close();
        }
        return array($list,$widgets);
    }
}
