<?php
namespace Kna\MQEventBundle\Event;


use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\EventDispatcher\Event;

class MessageQueueEvent extends Event
{
    /**
     * @var array
     */
    protected $data;

    /**
     * @var AMQPMessage
     */
    protected $message;

    /**
     * @var bool|int|null
     */
    protected $result = false;

    /**
     * MQEvent constructor.
     * @param AMQPMessage $message
     */
    public function __construct(AMQPMessage $message)
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getJsonData()
    {
        return $this->data = json_decode($this->message->getBody(), true);
    }

    /**
     * @return bool|int|null
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param bool|int|null $result
     */
    public function setResult($result): void
    {
        $this->result = $result;
    }

    public function ack(): void
    {
        $this->setResult(ConsumerInterface::MSG_ACK);
    }

    public function nackReque(): void
    {
        $this->setResult(ConsumerInterface::MSG_SINGLE_NACK_REQUEUE);
    }

    public function rejectReque(): void
    {
        $this->setResult(ConsumerInterface::MSG_REJECT_REQUEUE);
    }

    public function reject(): void
    {
        $this->setResult(ConsumerInterface::MSG_REJECT);
    }

    public function ackSent(): void
    {
        $this->setResult(ConsumerInterface::MSG_ACK_SENT);
    }
}