<?php

namespace App\Repository\Filters;

use Doctrine\ORM\Mapping\ClassMetaData;
use Doctrine\ORM\Query\Filter\SQLFilter;

class DeletedFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        if ($targetEntity->hasField("deletedAt")) {
            return $targetTableAlias . ".deleted_at IS NULL";
        }
        return "";
    }
}