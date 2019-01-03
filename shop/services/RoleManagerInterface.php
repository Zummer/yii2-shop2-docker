<?php
/**
 * Created by PhpStorm.
 * User: af
 * Date: 03.01.19
 * Time: 0:19
 */

namespace shop\services;

interface RoleManagerInterface
{
    public function assign($userId, $name): void;
}