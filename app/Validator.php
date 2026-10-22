<?php
declare(strict_types=1);

namespace App;

final class Validator
{
    /** @var array<string, string> */
    private array $errors = [];

    public function required(string $field, mixed $value, string $label): self
    {
        if (trim((string) $value) === '') {
            $this->errors[$field] = $label . ' is required.';
        }
        return $this;
    }

    public function email(string $field, mixed $value, string $label): self
    {
        if (trim((string) $value) !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = $label . ' must be a valid email address.';
        }
        return $this;
    }

    public function max(string $field, mixed $value, int $max, string $label): self
    {
        if (mb_strlen(trim((string) $value)) > $max) {
            $this->errors[$field] = $label . ' must be at most ' . $max . ' characters.';
        }
        return $this;
    }

    public function min(string $field, mixed $value, int $min, string $label): self
    {
        if (trim((string) $value) !== '' && mb_strlen(trim((string) $value)) < $min) {
            $this->errors[$field] = $label . ' must be at least ' . $min . ' characters.';
        }
        return $this;
    }

    public function in(string $field, mixed $value, array $allowed, string $label): self
    {
        if (trim((string) $value) !== '' && !in_array((string) $value, $allowed, true)) {
            $this->errors[$field] = $label . ' is not valid.';
        }
        return $this;
    }

    public function numeric(string $field, mixed $value, string $label): self
    {
        if (trim((string) $value) !== '' && !is_numeric($value)) {
            $this->errors[$field] = $label . ' must be a number.';
        }
        return $this;
    }

    public function date(string $field, mixed $value, string $label): self
    {
        if (trim((string) $value) === '') {
            return $this;
        }
        $dt = \DateTime::createFromFormat('Y-m-d', (string) $value);
        if (!$dt || $dt->format('Y-m-d') !== $value) {
            $this->errors[$field] = $label . ' must be a valid date.';
        }
        return $this;
    }

    public function between(string $field, mixed $value, int $min, int $max, string $label): self
    {
        $n = (int) $value;
        if ($n < $min || $n > $max) {
            $this->errors[$field] = $label . ' must be between ' . $min . ' and ' . $max . '.';
        }
        return $this;
    }

    public function honeypot(string $field = 'website_url'): self
    {
        if (trim((string) ($_POST[$field] ?? '')) !== '') {
            $this->errors['_spam'] = 'Unable to submit this form.';
        }
        return $this;
    }

    public function failed(): bool
    {
        return $this->errors !== [];
    }

    /** @return array<string, string> */
    public function errors(): array
    {
        return $this->errors;
    }

    public function add(string $field, string $message): self
    {
        $this->errors[$field] = $message;
        return $this;
    }
}
