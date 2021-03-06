<?php

namespace Codefog\TagsBundle\Test\EventListener\DataContainer;

use Codefog\TagsBundle\EventListener\DataContainer\TagListener;
use Codefog\TagsBundle\Manager\ManagerInterface;
use Codefog\TagsBundle\ManagerRegistry;
use Codefog\TagsBundle\Tag;
use Contao\DataContainer;
use PHPUnit\Framework\TestCase;

class TagListenerTest extends TestCase
{
    /**
     * @var TagListener
     */
    private $listener;

    public function setUp()
    {
        $manager = $this->createMock(ManagerInterface::class);
        $manager->method('find')->willReturn(new Tag('', ''));
        $manager->method('countSourceRecords')->willReturn(0);

        $registry = $this->createMock(ManagerRegistry::class);
        $registry->method('get')->willReturn($manager);
        $registry->method('getAliases')->willReturn(['foo', 'bar']);

        $this->listener = new TagListener($registry);
    }

    public function testInstantiation()
    {
        static::assertInstanceOf(TagListener::class, $this->listener);
    }

    public function testGenerateLabel()
    {
        require_once __DIR__.'/../../Fixtures/Backend.php';

        static::assertNotEmpty(
            $this->listener->generateLabel(
                ['source' => '', 'id' => ''],
                '',
                $this->createMock(DataContainer::class),
                ['foo' => 'bar']
            )
        );
    }

    public function testGetSources()
    {
        static::assertEquals(['foo', 'bar'], $this->listener->getSources());
    }
}
