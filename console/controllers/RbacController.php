<?php
/**
 * Created by PhpStorm.
 * User: af
 * Date: 02.01.19
 * Time: 12:42
 */

namespace console\controllers;

use common\rbac\UserOwnerRule;
use yii\console\Controller;

class RbacController extends Controller
{
    private $authManager;

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->authManager = \Yii::$app->getAuthManager();
    }

    public function actionAddUserOwnerRule() {
        $rule = new UserOwnerRule();
        $this->authManager->add($rule);
    }
}