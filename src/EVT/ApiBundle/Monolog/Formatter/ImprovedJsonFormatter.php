<?php

namespace EVT\ApiBundle\Monolog\Formatter;

use Monolog\Formatter\JsonFormatter;

/**
 * ImprovedJsonFormatter
 *
 * @author Eduardo Gulias Davis <eduardo.gulias@bodaclick.com>
 * @copyright Bodaclick S.A
 */
class ImprovedJsonFormatter extends JsonFormatter
{
    /**
     * {@inheritdoc}
     */
    public function format(array $record)
    {
        return json_encode($record) . PHP_EOL;
    }
}
