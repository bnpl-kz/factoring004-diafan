<?php

namespace BnplPartners\Factoring004\ChangeStatus;

use MyCLabs\Enum\Enum;

/**
 * @method static static CANCEL()
 *
 * @psalm-immutable
 */
final class CancelStatus extends Enum
{
    const CANCEL = 'canceled';
}