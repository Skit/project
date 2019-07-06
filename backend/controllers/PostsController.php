<?php
namespace backend\controllers;

use blog\fileManager\entities\Path;
use blog\fileManager\services\FileService;
use Yii;
use blog\managers\{PostManager, TagsManager, FormsManager};
use blog\fileManager\ImageManager;
use blog\fileManager\source\Image;
use common\services\{forms, models};
use backend\forms\{CategoryForm, MetaTagsForm, PostsForm, PostsSearch, TagsForm};
use backend\models\Posts;
use yii\base\{DynamicModel, Module};
use yii\web\{NotFoundHttpException, Response, UploadedFile};
use yii\filters\VerbFilter;

/**
 * PostsController implements the CRUD actions for Posts model.
 *
 * @property models\PostsService $PostsService
 * @property forms\CategoriesService $CategoriesService
 * @property FormsManager $FormsManager
 * @property PostManager $PostManager
 * @property TagsManager $TagsManager
 * @property ImageManager $ImageManager
 * @property FileService $FileService
 */
class PostsController extends Controller
{
    public function __construct
    (
        string $id, Module $module,
        FileService $fileService,
        models\PostsService $service,
        TagsManager $tags,
        forms\CategoriesService $categoriesFormsService,
        FormsManager $formsManager,
        PostManager $postManager,
        ImageManager $imageManager,
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
                    'AutoCompleteTags' => ['POST']
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
            $form = $this->FormsManager->getMainForm();
            $post = $this->PostManager->create($form);

            return $this->redirect(['view', 'id' => $post->id]);
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
     * @param $term
     * @return array
     */
    public function actionAutoCompleteTags($term)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $this->TagsManager->searchAutocomplete($term, true);
    }

    public function actionShowImages()
    {

    }

    public function actionUploadImage()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if ($file = UploadedFile::getInstanceByName('file')) {
            $model = new DynamicModel(compact('file'));
            $model->addRule('file', 'image', ['skipOnEmpty' => false, 'extensions' => 'png, jpg'])->validate();

            if($model->hasErrors()) {
                return ['error' => $model->getFirstError('file')];
            }

            /* @var Path $result */
            $paths = $this->ImageManager->imperaviResize(
                new Image($file->name, $file->tempName, $file->size, $file->type),
                Yii::$app->params['resizer']['patterns']['imperavi'],
                Yii::$app->params['resizer']['paths']
            );

            return [
                'filelink' => $paths->getDraftSave()->getSiteUrl(),
                'filename' => $paths->getSave()->fileName(),
            ];
        }

        return ['error' => 'empty file'];
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
