<?php

namespace Armincms\Contract\Gutenberg\Widgets;

trait HasRelationships
{
    /**
     * Get the realted model for the given relation ship.
     *
     * @return \Illuminate\Pagination\LengthAwarePagination
     */
    public function belongsTo(string $relationship)
    {
        return $this->queryRelationship($relationship)->first();
    }

    /**
     * Get the realted models for the given relation ship.
     *
     * @param  int  $perPage
     * @return \Illuminate\Pagination\LengthAwarePagination
     */
    public function hasMany(string $relationship, int $perPage = null)
    {
        return $this->queryRelationship($relationship)->paginate($perPage);
    }

    /**
     * Get the realted models for the given relation ship.
     *
     * @param  int  $perPage
     * @return \Illuminate\Pagination\LengthAwarePagination
     */
    public function belongsToMany(string $relationship, int $perPage = null)
    {
        return $this->queryRelationship($relationship, $perPage)->paginate();
    }

    /**
     * Build new query for the given relationship.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function queryRelationship(string $relationship)
    {
        return $this->newRelationModel($relationship)->whereHas($relationship, function ($query) use ($relationship) {
            return $query->whereKey($this->getRelatedKeys($relationship));
        })->tap(function ($query) use ($relationship) {
            return $this->applyRelationshipQuery($query, $relationship);
        });
    }

    /**
     * Create new related model instance.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function newRelationModel(string $relationship)
    {
        $model = static::relationModel($relationship);

        return new $model;
    }

    /**
     * Get the related model.
     */
    abstract protected static function relationModel(string $relationship): string;

    /**
     * Get the reated keys for the given relatinoship.
     *
     *
     * @return int|array
     */
    protected function getRelatedKeys(string $relationship)
    {
        return $this->getParent($relationship)->getKey();
    }

    /**
     * Get the parent model.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    abstract protected function getParent(string $relationship);

    /**
     * Apply custom query to the relationship query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $relationship
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applyRelationshipQuery($query, $relationship)
    {
        return $query;
    }
}
