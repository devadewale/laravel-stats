<?php

namespace Wnx\LaravelStats\Tests;

use Wnx\LaravelStats\Project;
use Illuminate\Support\Facades\Gate;
use Wnx\LaravelStats\ReflectionClass;
use Wnx\LaravelStats\Tests\Stubs\Rules\DemoRule;
use Wnx\LaravelStats\Statistics\ProjectStatistic;
use Wnx\LaravelStats\ValueObjects\ClassifiedClass;
use Wnx\LaravelStats\Tests\Stubs\Policies\DemoPolicy;
use Wnx\LaravelStats\Tests\Stubs\Models\Project as ProjectModel;

class ProjectTest extends TestCase
{
    /** @test */
    public function creates_a_project_object_from_a_collection_of_reflection_classes()
    {
        $classes = collect([
            $projectModel = new ReflectionClass(ProjectModel::class),
        ]);

        $project = new Project($classes);

        $this->assertTrue(
            $project->classifiedClasses()->map(function ($class) {
                return $class->reflectionClass;
            })->contains($projectModel)
        );
        $this->assertInstanceOf(ClassifiedClass::class, $project->classifiedClasses()->first());
    }

    /** @test */
    public function returns_instance_of_project_statistics_when_accessing_project_statistics()
    {
        $classes = collect([
            $projectModel = new ReflectionClass(ProjectModel::class),
        ]);

        $project = new Project($classes);

        $this->assertInstanceOf(ProjectStatistic::class, $project->statistic());
    }

    /** @test */
    public function groups_classes_into_components()
    {
        Gate::policy(\Wnx\LaravelStats\Tests\Stubs\Models\Project::class, \Wnx\LaravelStats\Tests\Stubs\Policies\DemoPolicy::class);

        $classes = collect([
            new ReflectionClass(DemoPolicy::class),
            new ReflectionClass(DemoRule::class),
        ]);

        $project = new Project($classes);

        $groupedByName = $project->classifiedClassesGroupedByComponentName();

        $this->assertArrayHasKey('Policies', $groupedByName);
        $this->assertCount(1, $groupedByName['Policies']);

        $this->assertArrayHasKey('Rules', $groupedByName);
        $this->assertCount(1, $groupedByName['Rules']);
    }

    /** @test */
    public function groups_classes_into_components_and_filters_by_component_name()
    {
        Gate::policy(\Wnx\LaravelStats\Tests\Stubs\Models\Project::class, \Wnx\LaravelStats\Tests\Stubs\Policies\DemoPolicy::class);

        $classes = collect([
            new ReflectionClass(DemoPolicy::class),
            new ReflectionClass(DemoRule::class),
        ]);

        $project = new Project($classes);
        $filter = ['Rules'];

        $groupedByName = $project->classifiedClassesGroupedAndFilteredByComponentNames($filter);

        $this->assertArrayNotHasKey('Policies', $groupedByName);
        $this->assertArrayHasKey('Rules', $groupedByName);
    }

    /** @test */
    public function groups_classes_into_component_and_does_not_apply_filter_if_array_is_empty()
    {
        Gate::policy(\Wnx\LaravelStats\Tests\Stubs\Models\Project::class, \Wnx\LaravelStats\Tests\Stubs\Policies\DemoPolicy::class);

        $classes = collect([
            new ReflectionClass(DemoPolicy::class),
            new ReflectionClass(DemoRule::class),
        ]);

        $project = new Project($classes);
        $filter = [];

        $groupedByName = $project->classifiedClassesGroupedAndFilteredByComponentNames($filter);

        $this->assertArrayHasKey('Policies', $groupedByName);
        $this->assertArrayHasKey('Rules', $groupedByName);
    }
}
