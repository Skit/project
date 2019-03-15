<?php
namespace backend\controllers;

use blog\managers\PostManager;
use Yii;
use blog\managers\FormsManager;
use common\services\{forms, models};
use backend\forms\{CategoryForm, MetaTagsForm, PostsForm, PostsSearch, TagsForm};
use backend\models\Posts;
use yii\base\Module;
use yii\web\{NotFoundHttpException};
use yii\filters\VerbFilter;

/**
 * PostsController implements the CRUD actions for Posts model.
 *
 * @property models\PostsService $PostsService
 * @property forms\CategoriesService $CategoriesService
 * @property FormsManager $FormsManager
 * @property PostManager $PostManager
 */
class PostsController extends Controller
{
    public function __construct
    (
        string $id, Module $module,
        models\PostsService $service,
        forms\CategoriesService $categoriesFormsService,
        FormsManager $formsManager,
        PostManager $postManager,
        array $config = []
    )
    {
        parent::__construct(func_get_args(), $id, $module, $config);

        $this->FormsManager
            ->mergeForm(new PostsForm())
            ->with(new TagsForm(), new MetaTagsForm(), new CategoryForm());
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Posts models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PostsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Posts model.
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
     * Creates a new Posts model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if ($this->FormsManager->loadPost() && $this->FormsManager->validate() && $this->FormsManager->mergeData()) {
            $this->PostManager->create($this->FormsManager->getMainForm());
        }

        return $this->render('create', [
            'forms' => $this->FormsManager->getForms(),
            'categories' => $this->CategoriesService
        ]);
    }

    /**
     * Updates an existing Posts model.
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
            $this->PostManager->edit($form);

            return $this->redirect(['view', 'id' => $form->id]);
        }

        return $this->render('update', [
            'forms' => $this->FormsManager->getForms(),
            'categories' => $this->CategoriesService
        ]);
    }

    /**
     * Deletes an existing Posts model.
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
     * Finds the Posts model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Posts the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Posts::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
