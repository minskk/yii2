<?php

namespace app\controllers;

use yii\web\NotFoundHttpException;
use yii\filters\{AccessControl, VerbFilter};
use yii\helpers\ArrayHelper;
use Itstructure\MFUploader\models\OwnerMediafile;
use app\models\Product;

/**
 * Class ProductController
 *
 * @package app\controllers
 */
class ProductController extends BaseController
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => ['?', '@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'view' => ['get'],
                ],
            ],
        ]);
    }

    /**
     * Displays product page.
     *
     * @return string
     *
     * @throws NotFoundHttpException
     */
    public function actionView($alias)
    {
        $model = Product::find()->where([
            'alias' => $alias
        ])->andWhere([
            'active' => 1
        ])->one();

        if (null === $model) {
            throw new NotFoundHttpException('Product not fount with alias = '.$alias.'.');
        }

        $this->setMetaParams($model);

        $images = OwnerMediafile::getImageFiles(Product::tableName(), $model->id);

        return $this->render('view', [
            'model' => $model,
            'images' => $images
        ]);
    }
}
