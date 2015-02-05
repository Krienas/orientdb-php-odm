<?php

namespace test\Doctrine\ODM\OrientDB\Document\Stub;

use Doctrine\ODM\OrientDB\Mapping\Annotations as ODM;

/**
* @ODM\Document(class="OCity")
*/
class City
{
    /**
     * @ODM\Property(name="@rid", type="string")
     */
    public $rid;

    private $name;
}
