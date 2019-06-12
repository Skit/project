<?php

namespace backend\controllers;

use backend\models\Categories;
use blog\managers\FormsManager;
use Yii;
use backend\forms\CategoriesForm;
use backend\forms\MetaTagsForm;
use backend\forms\CategoriesSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CategoriesController implements the CRUD actions for Categories model.
 *
 * @property FormsManager $FormsManager
 */
class CategoriesController extends Controller
{

    public function __construct($id, $module, FormsManager $formsManager, $config = [])
    {
        parent::__construct(func_get_args(), $id, $module, $config);

        $this->FormsManager
            ->mergeForm(new CategoriesForm())
            ->with(new MetaTagsForm());
    }
    /**
     * {@inheritdoc}
     */
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
     * Lists all Categories models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CategoriesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Categories model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Categories model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if ($this->FormsManager->loadPost() && $this->FormsManager->validate() && $this->FormsManager->mergeData()) {
            $form = $this->FormsManager->getMainForm();

            //return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'forms' => $this->FormsManager->getForms(),
        ]);
    }

    /**
     * Updates an existing Categories model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $this->FormsManager->fillForms($this->findModel($id));

        if ($this->FormsManager->loadPost() && $this->FormsManager->validate() && $this->FormsManager->mergeData()) {
            $form = $this->FormsManager->getMainForm();
            //$this->CategoryManager->edit($form);
            //return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'forms' => $this->FormsManager->getForms(),
        ]);
    }

    /**
     * Deletes an existing Categories model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Categories model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Categories the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Categories::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
