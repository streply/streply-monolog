<?php

namespace Streply\Monolog;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Streply\Streply;

class StreplyMonologHandler extends AbstractProcessingHandler
{
    use StreplyMonologHandlerProcessingHandlerTrait;
    use StreplyMonologHandlerParserTrait;

    private const EVENT_TYPE_LOG = 0;
    private const EVENT_TYPE_ERROR = 1;

    public function __construct(string $dsn, array $options = [], $level = Logger::DEBUG, bool $bubble = true)
    {
        parent::__construct($level, $bubble);

        if (Streply::isInitialize() === false) {
            Streply::Initialize($dsn, $options);
        }
    }

    protected function doWrite(array $record): void
    {
        $level = $this->parseLevel($record['level']);
        $message = $record['message'];
        $channel = $record['channel'] ?? '';
        $params = array_merge(
            [
                'monolog.version' => Logger::API,
                'monolog.channel' => $channel,
                'monolog.level_name' => $record['level_name'],
                'monolog.formatted' => $record['formatted'] ?? '',
            ],
            $this->parseContext($record['context'])
        );

        // Exception
        if (isset($record['context']['exception']) && $record['context']['exception'] instanceof \Throwable) {
            \Streply\Exception($record['context']['exception'], $params, $level);
            return;
        }

        // Error
        if($this->getEventType($record['level']) === self::EVENT_TYPE_ERROR) {
            \Streply\Error($message, $params, $level, $channel);
            return;
        }

        // Log
        \Streply\Log($message, $params, $level, $channel);
    }
}
