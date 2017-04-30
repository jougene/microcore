<?php

namespace Application\Schemas;

/**
 * Created by PhpStorm.
 * User: lcdee
 * Date: 29.04.2017
 * Time: 17:48
 */
class TestSchema extends \Neomerx\JsonApi\Schema\SchemaProvider
{
    protected $resourceType = 'test';
    /**
     * Get resource identity.
     *
     * @param object $resource
     *
     * @return string
     */
    public function getId($resource)
    {
        return $resource->id;
    }

    /**
     * Get resource attributes.
     *
     * @param object $resource
     *
     * @return array
     */
    public function getAttributes($resource)
    {
        return [
            'text' => $resource->text
        ];
    }
}