<?php
declare(strict_types = 1);

namespace Maksi\LaravelRequestMapper\Tests\Integration;

use Illuminate\Http\Request;
use Maksi\LaravelRequestMapper\Tests\Integration\Stub\NestedRequestData\RootRequestDataStub;

/**
 * Class NestedResolvingTest
 *
 * @package Maksi\LaravelRequestMapper\Tests\Integration
 */
class NestedResolvingTest extends TestCase
{
    public function testValidNestedRequestData(): void
    {
        /** @var Request $request */
        $request = $this->app->make(Request::class);
        $request->json()->set('title', 'title1');
        $request->json()->set('nested', ['title' => 'nested_title']);

        /** @var RootRequestDataStub $dto */
        $dto = $this->app->make(RootRequestDataStub::class);
        $this->assertInstanceOf(RootRequestDataStub::class, $dto);
        $this->assertSame('title1', $dto->getTitle());
        $this->assertSame('nested_title', $dto->getNested()->getTitle());
    }

    /**
     * @expectedException \Maksi\LaravelRequestMapper\Validation\ResponseException\JsonResponsableException
     */
    public function testInvalidNestedRequestData(): void
    {
        /** @var Request $request */
        $request = $this->app->make(Request::class);
        $request->json()->set('title', 'title1');
        $request->json()->set('nested', []);

        /** @var RootRequestDataStub $dto */
        $dto = $this->app->make(RootRequestDataStub::class);
        $this->assertInstanceOf(RootRequestDataStub::class, $dto);
        $this->assertSame('title1', $dto->getTitle());
    }
}
