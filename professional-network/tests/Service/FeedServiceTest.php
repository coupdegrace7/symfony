<?php
namespace App\Tests\Service;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Service\FeedService;

class FeedServiceTest extends KernelTestCase
{
    public function testGetFeedReturnsArray()
    {
        self::bootKernel();
        $container = static::getContainer();
        $feed = $container->get(FeedService::class)->getFeed($container->get(\Doctrine\Persistence\ObjectRepository::class)->find(1));
        $this->assertIsArray($feed);
    }
}
