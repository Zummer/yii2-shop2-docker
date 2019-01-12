<?php
namespace shop\tests\unit\entities\User;
use Codeception\Test\Unit;
use PHPUnit\Framework\TestResult;
use shop\entities\User\User;
class SignupByNetworkTest extends Unit
{
    public function testSuccess()
    {
        $user = User::signupByNetwork(
            $network = 'vk',
            $identity = '123456',
            $email = '123@mail.com'
        );
        $this->assertCount(1, $networks = $user->networks);
        $this->assertEquals($identity, $networks[0]->identity);
        $this->assertEquals($network, $networks[0]->network);
        $this->assertNotEmpty($user->created_at);
        $this->assertNotEmpty($user->auth_key);
        $this->assertTrue($user->isActive());
    }

    public function run(TestResult $result = null)
    {
        // TODO: Implement run() method.
    }

    public function count()
    {
        // TODO: Implement count() method.
    }
}
