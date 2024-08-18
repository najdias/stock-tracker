<?php

namespace App\Tests\Unit\Repository;

use App\Entity\StockLog;
use App\Entity\User;
use App\Repository\StockLogRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\User\UserInterface;

class StockLogRepositoryTest extends KernelTestCase
{
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
    }

    public function testFindAllByUserOrderedByDateReturnsLogsForUser(): void
    {
        $user = (new User())
            ->setEmail('aaaa@aaa.aa')
            ->setPassword('aaaa');
        $this->entityManager->persist($user);

        $log = (new StockLog())
            ->setUser($user)
            ->setSymbol('AAPL')
            ->setOpen(100.0)
            ->setHigh(110.0)
            ->setLow(90.0)
            ->setClose(105.0)
            ->setDate(new \DateTime());
        $this->entityManager->persist($log);
        $this->entityManager->flush();

        $repository = $this->entityManager->getRepository(StockLog::class);

        $logs = $repository->findAllByUserOrderedByDate($user);

        $this->assertNotEmpty($logs);
        $this->assertEquals('AAPL', $logs[0]['symbol']);
        $this->assertEquals(100.0, $logs[0]['open']);
        $this->assertEquals(110.0, $logs[0]['high']);
        $this->assertEquals(90.0, $logs[0]['low']);
        $this->assertEquals(105.0, $logs[0]['close']);
    }

    public function testFindAllByUserOrderedByDateReturnsEmptyArrayForNoLogs(): void
    {
        $user = (new User())
            ->setEmail('bbbb@bbb.bb')
            ->setPassword('bbbb');
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $repository = $this->entityManager->getRepository(StockLog::class);

        $logs = $repository->findAllByUserOrderedByDate($user);

        $this->assertIsArray($logs);
        $this->assertEmpty($logs);
    }
}
