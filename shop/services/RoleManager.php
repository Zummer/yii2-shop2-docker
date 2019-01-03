<?php
/**
 * Created by PhpStorm.
 * User: af
 * Date: 02.01.19
 * Time: 20:53
 */

namespace shop\services;

use yii\rbac\ManagerInterface;

class RoleManager implements RoleManagerInterface
{
    private $manager;

    public function __construct(ManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function assign($userId, $name): void
    {
        if (!$role = $this->manager->getRole($name)) {
            throw new \DomainException('Role "' . $name . '" does not exist.');
        }
        $this->manager->revokeAll($userId);
        $this->manager->assign($role, $userId);
    }
}