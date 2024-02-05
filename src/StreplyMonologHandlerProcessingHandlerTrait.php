<?php

namespace Streply\Monolog;

use Monolog\LogRecord;
use Monolog\Level;
use Monolog\Logger;
use Streply\Enum\Level as StreplyEnumLevel;

if (Monolog\Logger::API >= 3) {
    trait StreplyMonologHandlerProcessingHandlerTrait
    {
        abstract protected function doWrite(array $record): void;

        protected function write(LogRecord $record): void
        {
            $this->doWrite($record->toArray());
        }

        private function getEventType(int $level): int
        {
            $level = Level::from($level);

            switch ($level) {
                case Level::Error:
                case Level::Critical:
                case Level::Alert:
                    return self::EVENT_TYPE_ERROR;
            }

            return self::EVENT_TYPE_LOG;
        }

        private function parseLevel(int $level): string
        {
            $level = Level::from($level);

            switch ($level) {
                case Level::Warning:
                    return StreplyEnumLevel::HIGH;
                case Level::Emergency:
                case Level::Critical:
                case Level::Alert:
                    return StreplyEnumLevel::CRITICAL;
            }

            return StreplyEnumLevel::NORMAL;
        }
    }
} else {
    trait StreplyMonologHandlerProcessingHandlerTrait
    {
        abstract protected function doWrite(array $record): void;

        protected function write(array $record): void
        {
            $this->doWrite($record);
        }

        private function getEventType(int $level): int
        {
            switch ($level) {
                case Monolog\Logger::ERROR:
                case Monolog\Logger::CRITICAL:
                case Monolog\Logger::ALERT:
                    return self::EVENT_TYPE_ERROR;
            }

            return self::EVENT_TYPE_LOG;
        }

        private function parseLevel(int $level): string
        {
            switch ($level) {
                case Logger::WARNING:
                    return StreplyEnumLevel::HIGH;
                case Logger::CRITICAL:
                case Logger::EMERGENCY:
                case Logger::ALERT:
                    return StreplyEnumLevel::CRITICAL;
            }

            return StreplyEnumLevel::NORMAL;
        }
    }
}