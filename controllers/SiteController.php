<?php

namespace app\controllers;

use app\component\Common\LogClass;
use app\component\Common\FileClass;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\helpers\Json;
use app\component\Parser\ParserClass;
use yii\filters\VerbFilter;

class SiteController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only'  => ['logout'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs'  => [
                'class' => VerbFilter::className(),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error'   => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class'           => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Абработка запроса /site/index
     *
     * @return string
     */
    public function actionIndex()
    {
        Yii::$app->view->title = 'Анализатор доходности';

        return $this->render('index');
    }

    /**
     * Обработка запроса получения данных для файла
     * post запрос
     * на входе название файла
     * @return bool|string
     */
    public function actionGetData()
    {
        $name_file = Yii::$app->request->post('name_file', null);

        $parser = new ParserClass($name_file);

        if ($parser->isValid()) {
            return Json::encode([
                    'hasErrors' => false,
                    'full_info' => $parser->getFullInfo()
                ]);
        }

        return Json::encode([
                'hasErrors' => true,
                'message' => $parser->getMessage()
            ]);
    }

    /**
     * Обеспечивает загрузку файла
     * @return string
     */
    public function actionUploadFile()
    {
        $result_upload = [];

        if (empty($_FILES)) {
            $result_upload = [
                'hasErrors'  => true,
                'message' => 'Не загружен файл'
            ];

            return Json::encode($result_upload);
        }

        $obj_file_class = new FileClass();
        $obj_file_class->setAllowedExtension(['html', 'htm']);
        $obj_file_class->setAllowedSize(4);
        $obj_file_class->setDestination(filter_input(INPUT_SERVER , 'DOCUMENT_ROOT') . "/web/files/");

        if ($obj_file_class->isValid()) {
            $obj_file_class->receive();
            $name_file = $obj_file_class->getNameFileOnServer();

            $report = new ParserClass($name_file);
            if (!$report->isValid() ){
                $result_upload = [
                    'hasErrors' => true,
                    'message' => $report->getMessage()
                ];
            } else {
                $result_upload = [
                    'hasErrors' => false,
                    'file_name' => $name_file,
                    'full_info' => $report->getFullInfo()
                ];
            }
               
        } else {
            $result_upload = [
                'hasErrors' => true,
                'message' => $obj_file_class->getMessage()
            ];
        }

        return json_encode($result_upload);
    }

}