<?php
namespace shop\services\manage;

use shop\entities\User\User;
use shop\forms\manage\User\UserCreateForm;
use shop\forms\manage\User\UserEditForm;
use shop\repositories\UserRepositoryInterface;
use shop\services\RoleManagerInterface;
use shop\services\TransactionManagerInterface;

class UserManageService implements UserManageServiceInterface
{
    private $repository;
    private $roles;
    private $transaction;

    public function __construct(UserRepositoryInterface $repository, RoleManagerInterface $roles, TransactionManagerInterface $transaction)
    {
        $this->repository = $repository;
        $this->roles = $roles;
        $this->transaction = $transaction;
    }

    public function create(UserCreateForm $form): User
    {
        $password = !empty($form->password) ? $form->password : '';
        $user = User::create($form->username, $form->email, $password);

        $this->transaction->wrap(function () use ($user, $form) {
            $this->repository->save($user);
            $this->roles->assign($user->id, $form->role);
        });

        return $user;
    }

    public function assignRole($id, $role): void
    {
        $user = $this->repository->get($id);
        $this->roles->assign($user->id, $role);
    }

    public function edit($id, UserEditForm $form): void
    {
        $user = $this->repository->get($id);
        $description = !empty($form->description) ? $form->description : '';

        $user->edit($form->username, $form->email, $description);

        $this->transaction->wrap(function () use ($user, $form) {
            $this->repository->save($user);
            $this->roles->assign($user->id, $form->role);
        });
    }

    public function remove($id): void
    {
        $user = $this->repository->get($id);
        $this->repository->delete($user);
    }
}
