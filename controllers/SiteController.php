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
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionGetData()
    {
        LogClass::cleare();
        LogClass::saveLog('actionGetData');

        $parser = new ParserClass('statement1.html');

        if ($parser->loadFile()) {
            $report = $parser->getReportByType();

            $data = $report->getFullInfo();

            return json_encode($data);
        }

        return false;
    }

    public function actionUploadFile()
    {
        $result_upload = [];

        if (empty($_FILES)) {
            $result_upload = [
                'status'  => 'error',
                'message' => 'не загружен файл'
            ];

            return Json::encode($result_upload);
        }

        $obj_file_class = new FileClass();
        $obj_file_class->setAllowedExtension(['html', 'htm']);
        $obj_file_class->setAllowedSize(4);
        $obj_file_class->setDestination($_SERVER['DOCUMENT_ROOT'] . "/web/files/");

        if ($obj_file_class->isValid()) {
            $obj_file_class->receive();
            $result_upload['file_name'] = $obj_file_class->getNameFileOnServer();
            $result_upload['status'] = 'ok';
               
        } else {
            $result_upload['status'] = 'error';
            $result_upload['message'] = $obj_file_class->getMessage();
        }

        return Json::encode($result_upload);
    }

}