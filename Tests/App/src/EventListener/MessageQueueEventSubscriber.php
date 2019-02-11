<?php
namespace Kna\MQEventBundle\Tests\App\EventListener;


use Kna\MQEventBundle\Event\MessageQueueEvent;
use Kna\MQEventBundle\Tests\App\Events;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MessageQueueEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            Events::APP_EVENT => 'onMessageQueueEvent'
        ];
    }

    public function onMessageQueueEvent(MessageQueueEvent $event)
    {
        $data = $event->getJsonData();

        if (!$data) {
            $event->reject();
            throw new \RuntimeException('No data');
        }

        $this->logger->debug(json_encode($data));
        $event->ack();
    }
}