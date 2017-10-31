<?php

namespace CtiDigital\Configurator\Test\Unit\Component;

use CtiDigital\Configurator\Component\ComponentAbstract;
use CtiDigital\Configurator\Model\LoggerInterface;
use Magento\Framework\ObjectManagerInterface;

/**
 * Class ComponentAbstractTestCase
 * @package CtiDigital\Configurator\Test\Unit\Component
 *
 * @SuppressWarnings(PHPMD.NumberOfChildren)
 */
abstract class ComponentAbstractTestCase extends \PHPUnit_Framework_TestCase
{

    /* @var $component ComponentAbstract */
    protected $component;

    /* @var $className String */
    protected $className;

    /* @var $logInterface LoggerInterface */
    protected $logInterface;

    /* @var $testObjectManager \Magento\Framework\TestFramework\Unit\Helper\ObjectManager */
    protected $testObjectManager;

    /** @var $objectManager \Magento\Framework\ObjectManagerInterface */
    protected $objectManager;

    abstract protected function componentSetUp();

    protected function setUp()
    {
        $this->testObjectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->objectManager = $this->getMock(ObjectManagerInterface::class);
        $this->logInterface = $this->getMock(LoggerInterface::class);
        $this->componentSetUp();
    }

    public function testItExtendsAbstract()
    {
        $this->assertInstanceOf(ComponentAbstract::class, $this->component);
    }

    public function testItHasAnAlias()
    {
        $this->assertClassHasAttribute('alias', $this->className);
        $this->assertNotEmpty(
            $this->component->getComponentAlias(),
            sprintf('No alias specified in component %s', $this->className)
        );
    }

    public function testItHasAName()
    {
        $this->assertClassHasAttribute('name', $this->className);
        $this->assertNotEmpty(
            $this->component->getComponentName(),
            sprintf('No name specified in component %s', $this->className)
        );
    }
}
