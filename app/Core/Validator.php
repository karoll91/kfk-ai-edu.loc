<?php

declare(strict_types=1);

namespace App\Core;

class Validator
{
    private array $errors = [];
    private array $data   = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public static function make(array $data, array $rules): self
    {
        $v = new self($data);
        foreach ($rules as $field => $ruleStr) {
            $v->apply($field, explode('|', $ruleStr));
        }
        return $v;
    }

    private function apply(string $field, array $rules): void
    {
        $value = $this->data[$field] ?? null;

        foreach ($rules as $rule) {
            [$ruleName, $param] = array_pad(explode(':', $rule, 2), 2, null);

            match ($ruleName) {
                'required' => (!isset($value) || $value === '')
                    ? $this->addError($field, "{$field} majburiy")
                    : null,

                'email' => ($value && !filter_var($value, FILTER_VALIDATE_EMAIL))
                    ? $this->addError($field, "Email noto'g'ri formatda")
                    : null,

                'min' => ($value !== null && mb_strlen((string)$value) < (int)$param)
                    ? $this->addError($field, "{$field} kamida {$param} ta belgi bo'lishi kerak")
                    : null,

                'max' => ($value !== null && mb_strlen((string)$value) > (int)$param)
                    ? $this->addError($field, "{$field} ko'pi bilan {$param} ta belgi bo'lishi mumkin")
                    : null,

                'numeric' => ($value !== null && !is_numeric($value))
                    ? $this->addError($field, "{$field} raqam bo'lishi kerak")
                    : null,

                'in' => ($value !== null && !in_array($value, explode(',', $param ?? ''), true))
                    ? $this->addError($field, "{$field} noto'g'ri qiymat")
                    : null,

                'confirmed' => ($value !== ($this->data[$field . '_confirmation'] ?? null))
                    ? $this->addError($field, "Parollar mos kelmadi")
                    : null,

                default => null,
            };
        }
    }

    private function addError(string $field, string $message): void
    {
        $this->errors[$field][] = $message;
    }

    public function fails(): bool
    {
        return !empty($this->errors);
    }

    public function passes(): bool
    {
        return empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function firstError(): string
    {
        foreach ($this->errors as $messages) {
            return $messages[0];
        }
        return '';
    }
}
