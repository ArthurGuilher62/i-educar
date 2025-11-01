<?php

namespace Tests\Unit\Menu;

use App\Menu;
use App\Models\LegacyUserType;
use App\Process;
use Illuminate\Support\Collection as LaravelCollection;
use Tests\TestCase;

class MenuProcessesTest extends TestCase
{
    public function test_processes_when_no_process_and_parent()
    {
        $menu = $this->partialMock(Menu::class, function ($mock) {
            $mock->shouldReceive('getAttribute')->with('process')->andReturn(null);
            $mock->shouldReceive('getAttribute')->with('parent_id')->andReturn(null);
            $mock->shouldReceive('processes')->andReturn(new LaravelCollection());
        });

        $result = $menu->processes(
            'Test Path',
            new LaravelCollection(),
            LegacyUserType::LEVEL_ADMIN
        );

        $this->assertInstanceOf(LaravelCollection::class, $result);
        $this->assertCount(0, $result);
    }

    public function test_processes_when_admin_user_with_excluded_process()
    {
        $menu = $this->partialMock(Menu::class, function ($mock) {
            $mock->shouldReceive('getAttribute')->with('process')->andReturn(Process::CONFIG);
            $mock->shouldReceive('getAttribute')->with('parent_id')->andReturn(1);
            $mock->shouldReceive('getAttribute')->with('title')->andReturn('Config Menu');
            $mock->shouldReceive('processes')->andReturn(new LaravelCollection([
                ['title' => 'Config Menu']
            ]));
        });

        $result = $menu->processes(
            'Test Path',
            new LaravelCollection([1 => 1]),
            LegacyUserType::LEVEL_ADMIN
        );

        $this->assertInstanceOf(LaravelCollection::class, $result);
        $this->assertCount(1, $result);
        $this->assertEquals('Config Menu', $result->first()['title']);
    }

    public function test_processes_when_non_admin_user_with_excluded_process()
    {
        $menu = $this->partialMock(Menu::class, function ($mock) {
            $mock->shouldReceive('getAttribute')->with('process')->andReturn(Process::CONFIG);
            $mock->shouldReceive('getAttribute')->with('parent_id')->andReturn(1);
            $mock->shouldReceive('getAttribute')->with('title')->andReturn('Config Menu');
            $mock->shouldReceive('processes')->andReturn(new LaravelCollection());
        });

        $result = $menu->processes(
            'Test Path',
            new LaravelCollection(),
            LegacyUserType::LEVEL_INSTITUTIONAL
        );

        $this->assertInstanceOf(LaravelCollection::class, $result);
        $this->assertCount(0, $result);
    }
}
