<?php

namespace App\EventListener;

use App\Entity\Shape;
use App\Message\ComputeAreaMessage;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsEntityListener(event: Events::postUpdate, method: 'postUpdate', entity: Shape::class)]
#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: Shape::class)]
class ShapeListener
{
    public function __construct(
        private readonly MessageBusInterface $messageBusInterface
    ) {
    }

    public function postUpdate(Shape $shape, PostUpdateEventArgs $args): void
    {
        $this->sendMessageToQueue($shape->getId());
    }

    public function postPersist(Shape $shape, PostPersistEventArgs $args): void
    {
        $this->sendMessageToQueue($shape->getId());
    }

    private function sendMessageToQueue(int $shapeId): void
    {
        $message = new ComputeAreaMessage($shapeId, 'computeArea');
        $this->messageBusInterface->dispatch($message);
    }
}
