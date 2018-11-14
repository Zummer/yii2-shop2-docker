<?php
namespace shop\tests\unit\repositories;

use shop\repositories\BaseUserRepositoryTest;
use shop\repositories\MemoryUserRepository;

class MemoryUserRepositoryTest extends BaseUserRepositoryTest
{
    /**
     * @var \UnitTester
     */
    public $tester;

    public function _before(): void
    {
        $this->repository = new MemoryUserRepository();
    }
}
