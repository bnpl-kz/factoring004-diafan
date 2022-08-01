<?php

namespace BnplPartners\Factoring004\PreApp;

use BnplPartners\Factoring004\ArrayInterface;

/**
 * @psalm-immutable
 */
class ValidationErrorDetail implements ArrayInterface
{
    /**
     * @var string
     */
    private $error;
    /**
     * @var string
     */
    private $field;

    /**
     * @param string $error
     * @param string $field
     */
    public function __construct($error, $field)
    {
        $error = (string) $error;
        $field = (string) $field;
        $this->error = $error;
        $this->field = $field;
    }

    /**
     * @param array<string, string> $detail
     * @psalm-param array{error: string, field: string} $detail
     * @return \BnplPartners\Factoring004\PreApp\ValidationErrorDetail
     */
    public static function createFromArray($detail)
    {
        return new ValidationErrorDetail($detail['error'], $detail['field']);
    }

    /**
     * @param array<string, string>[] $details
     *
     * @psalm-param array{error: string, field: string}[] $details
     *
     * @return mixed[]
     */
    public static function createMany($details)
    {
        return array_map([ValidationErrorDetail::class, 'createFromArray'], $details);
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @return mixed[]
     * @psalm-return array{error: string, field: string}
     */
    public function toArray()
    {
        return [
            'error' => $this->getError(),
            'field' => $this->getField(),
        ];
    }
}
