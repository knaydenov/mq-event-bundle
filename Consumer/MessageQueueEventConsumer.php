<?php
namespace Kna\MQEventBundle\Consumer;


use Kna\MQEventBundle\Event\MessageQueueEvent;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class MessageQueueEventConsumer implements ConsumerInterface
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        LoggerInterface $logger
    )
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->logger = $logger;
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    /**
     * @return array
     */
    abstract public function getEventMap(): array;

    protected function getEventName(string $key): ?string
    {
        return $this->getEventMap()[$key] ?? null;
    }

    protected function handleUnknownRoutingKey(MessageQueueEvent $event): void
    {
        $event->reject();
    }

    /**
     * @param AMQPMessage $msg The message
     * @return mixed false to reject and requeue, any other value to acknowledge
     */
    public function execute(AMQPMessage $msg)
    {
        $event = new MessageQueueEvent($msg);

        try {
            $routingKey = $msg->get('routing_key');

            if ($eventName = $this->getEventName($routingKey)) {
                $this->eventDispatcher->dispatch($eventName, $event);
                return $event->getResult();
            } else {
                $this->handleUnknownRoutingKey($event);
                throw new \RuntimeException(sprintf('Unknown routing key `%s`', $routingKey));
            }
        } catch (\Exception $exception) {
            $this->logger->error(sprintf('%s: %s', substr(strrchr(__CLASS__, "\\"), 1), $exception->getMessage()));
            return $event->getResult();
        }
    }
}