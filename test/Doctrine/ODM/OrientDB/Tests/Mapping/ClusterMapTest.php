<?php

namespace Doctrine\ODM\OrientDB\Tests\Mapping;


use Doctrine\ODM\OrientDB\Mapping\ClusterMap;
use Doctrine\OrientDB\Binding\BindingInterface;
use Doctrine\OrientDB\Types\Rid;
use PHPUnit\TestCase;

class ClusterMapTest extends TestCase
{
    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|BindingInterface
     */
    protected function createBinding() {
        $binding = $this->getMockBuilder('\Doctrine\OrientDB\Binding\HttpBinding')
                        ->disableOriginalConstructor()
                        ->getMock();

        $binding->expects($this->once())
                ->method('getDatabaseName')
                ->will($this->returnValue(TEST_ODB_DATABASE));

        return $binding;
    }

    protected function createCache($filled = false) {
        $cache = $this->getMock('\Doctrine\Common\Cache\Cache');

        $cache->expects($this->once())
              ->method('contains')
              ->with($this->getCacheKey())
              ->will($this->returnValue($filled));

        if ($filled) {
            $cache->expects($this->once())
                  ->method('fetch')
                  ->with($this->getCacheKey())
                  ->will($this->returnValue($this->prepareMap()));
        }

        return $cache;
    }

    protected function prepareMap() {
        return ['Test' => [1, 2]];
    }

    protected function prepareGeneration($binding, $cache) {
        $data = [
            'classes' => [
                [
                    'name' => 'Test',
                    'clusters' => [1,2],
                ]
            ]
        ];

        $binding->expects($this->once())
                ->method('getDatabaseInfo')
                ->will($this->returnValue($data));

        $cache->expects($this->once())
              ->method('save')
              ->with($this->getCacheKey(), $this->prepareMap());
    }

    protected function getCacheKey() {
        return sprintf(ClusterMap::CACHE_KEY, TEST_ODB_DATABASE);
    }

    public function testIdentifyClassWithCache() {
        $binding = $this->createBinding();
        $cache   = $this->createCache(true);

        $clusterMap = new ClusterMap($binding, $cache);
        $this->assertEquals('Test', $clusterMap->identifyClass('#1:0'));
    }

    public function testIdentifyClassWithoutCache() {
        $binding = $this->createBinding();
        $cache   = $this->createCache();
        $this->prepareGeneration($binding, $cache);

        $clusterMap = new ClusterMap($binding, $cache);
        $this->assertEquals('Test', $clusterMap->identifyClass('#1:0'));
    }

    /**
     * @expectedException \Doctrine\ODM\OrientDB\Mapping\MappingException
     */
    public function testIdentifyClassNotFound() {
        $binding = $this->createBinding();
        $cache   = $this->createCache(true);

        $clusterMap = new ClusterMap($binding, $cache);
        $clusterMap->identifyClass('#10:0');
    }

} 