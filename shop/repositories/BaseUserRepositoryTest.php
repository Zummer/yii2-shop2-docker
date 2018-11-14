<?php
namespace shop\repositories;

use \Codeception\Test\Unit;
use shop\entities\User\User;

abstract class BaseUserRepositoryTest extends Unit
{
    /**
     * @var UserRepositoryInterface
     */
    protected $repository;

    public function testSave(): void
    {
        $user = new User();
        $user->id = 1;
        $user->email = 'sdf@sdf.ru';

        $this->repository->save($user);
        $found = $this->repository->getByEmail($user->email);
        $this->assertTrue($found->id == $user->id);
    }
}