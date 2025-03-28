<?php

declare(strict_types=1);

namespace Tempest\Database\Tests\Relations;

use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;
use Tempest\Database\Builder\ModelDefinition;
use Tempest\Database\Exceptions\InvalidRelation;
use Tempest\Database\Tests\Relations\Fixtures\HasOneParentModel;

/**
 * @internal
 */
final class HasOneRelationTest extends TestCase
{
    #[TestWith(['inversePropertyNotFound'], 'not found')]
    #[TestWith(['inversePropertyMissing'], 'missing property')]
    #[TestWith(['inversePropertyInvalid'], 'invalid type')]
    public function test_invalid_relations(string $relationName): void
    {
        $this->expectException(InvalidRelation::class);

        $definition = new ModelDefinition(HasOneParentModel::class);
        $definition->getRelations($relationName);
    }

    public function test_has_one_relation(): void
    {
        $definition = new ModelDefinition(HasOneParentModel::class);
        $autoResolvedRelation = $definition->getRelations('relatedModel');
        $namedRelation = $definition->getRelations('otherRelatedModel');

        $this->assertCount(1, $autoResolvedRelation);
        $this->assertCount(1, $namedRelation);
        $this->assertSame('has_one_parent_model.relatedModel', $autoResolvedRelation[0]->getRelationName());
        $this->assertSame('has_one_parent_model.otherRelatedModel', $namedRelation[0]->getRelationName());
    }
}
