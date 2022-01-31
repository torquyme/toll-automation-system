<?php
/**
 * UserControllerTest.php
 */

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Collection;
use Illuminate\Testing\Fluent\AssertableJson;
use Mockery\MockInterface;

class UserControllerTest extends \TestCase
{
    public function testAllEmpty()
    {
        $response = $this->get('/api/users');
        $response->assertResponseStatus(200);
        $response->receiveJson([]);
    }

    public function testFind()
    {
        $user = (new User())->setName("Test")->setSurname("test")->setEmail("test");

        $this->app->instance(UserService::class, \Mockery::mock(UserService::class, function (MockInterface $mock) use ($user) {
            $mock->shouldReceive('get')->andReturn($user);
        }));

        $users = $this->get('/api/user');
        $users->response->assertJson(function (AssertableJson $json) {
            $json->where("name", "Test");
        });
    }

    public function testAll()
    {
        $users = Collection::times(5, function () {
           return (new User())->setName("Test");
        });;

        $this->app->instance(UserService::class, \Mockery::mock(UserService::class, function (MockInterface $mock) use ($users) {
            $mock->shouldReceive('all')->andReturn($users);
        }));

        $users = $this->get('/api/users');
        $users->response->assertJson(function (AssertableJson $json) {
            $json->count(5);
        });
    }

    public function testInvoices()
    {
        $this->artisan('db:seed');

        $user = User::first();
        $this->artisan("invoice:monthly-for-user {$user->getId()}");

        $response = $this->json('get', '/api/user/invoices', ['id' => $user->getId()]);
        $response->assertResponseStatus(200);
        $response->response->assertJson(function(AssertableJson $json) {
            $json->first(function(AssertableJson $json) {
                $json->hasAll(['id', 'amount', 'routes', 'user', 'device', 'createdAt']);
            });
        });
    }

    public function testCreate()
    {
        $user = $this->post('/api/user', ['name' => 'Test', 'surname' => 'Test', 'email' => 'test@test.com']);
        $user->response->assertJson(function(AssertableJson $json) {
            $json->hasAll(
                ['id','name', 'surname', 'email', 'devices']
            );
        });
    }
}
