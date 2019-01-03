<?php
/**
 * Created by PhpStorm.
 * User: af
 * Date: 02.01.19
 * Time: 21:04
 */

namespace shop\services;

class TransactionManager implements TransactionManagerInterface
{
    public function wrap(callable $function): void
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $function();
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
}