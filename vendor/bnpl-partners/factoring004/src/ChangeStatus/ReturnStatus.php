<?php

namespace BnplPartners\Factoring004\ChangeStatus;

use MyCLabs\Enum\Enum;

/**
 * @method static static RE_TURN()
 * @method static static RETURN() Since PHP 7.0
 * @method static static PARTRETURN()
 *
 * @psalm-immutable
 */
final class ReturnStatus extends Enum
{
    const RE_TURN = 'return';
    const PARTRETURN = 'part_return';

    public static function __callStatic($name, $arguments)
    {
        if ($name === 'RETURN') {
            $name = 'RE_TURN';
        }

        return parent::__callStatic($name, $arguments);
    }
}
