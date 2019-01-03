<?php
/**
 * Created by PhpStorm.
 * User: af
 * Date: 03.01.19
 * Time: 0:25
 */

namespace shop\services;

interface TransactionManagerInterface
{
    public function wrap(callable $function): void;
}