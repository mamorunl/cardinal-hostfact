<?php

namespace Tnpdigital\Cardinal\Hostfact\Traits;

trait HasRelationships
{
    /**
     * Define a one-to-many relationship.
     *
     * @param  string  $related
     * @param  string|null  $foreignKey
     * @param  string|null  $localKey
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function hasMany($related, $foreignKey = null, $localKey = null)
    {
        $instance = new $related;


    }

    private function newHasMany()
    {

    }
}