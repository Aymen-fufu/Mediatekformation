<?php



class PublishedAtTest extends \PHPUnit\Framework\TestCase
{
    public function testPublishedAt()
    {
        $formation = new \App\Entity\Formation();
        $formation->setPublishedAt(new \DateTimeImmutable('2023-10-01'));

        $this->assertInstanceOf(\DateTimeImmutable::class, $formation->getPublishedAt());
        $this->assertEquals('2023-10-01', $formation->getPublishedAt()->format('Y-m-d'));
    }
}