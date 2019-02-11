<?php
namespace Kna\MQEventBundle\Tests\App\Consumer;


use Kna\MQEventBundle\Consumer\MessageQueueEventConsumer;
use Kna\MQEventBundle\Tests\App\Events;

class DefaultConsumer extends MessageQueueEventConsumer
{

    /**
     * @return array
     */
    public function getEventMap(): array
    {
        return [
            'some.event' => Events::APP_EVENT
        ];
    }
}