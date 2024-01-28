<?php

namespace App\Utils;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;

class Validator
{
    /**
     * Input data
     *
     * @var array|null
     */
    protected ?array $inputData;

    /**
     * Validations rules
     *
     * @var array
     */
    protected array $validationRules;

    /**
     * Validation result
     *
     * @var bool
     */
    protected bool $validationPassed = false;

    /**
     * Validation errors
     *
     * @var array
     */
    protected array $validationErrors = [];

    /**
     * Set the input data
     *
     * @param array|null $data The input data
     * @return self
     */
    public function setInputData(?array $data): self
    {
        $this->inputData = $data;

        return $this;
    }

    /**
     * Set the validation rules for the email field
     *
     * @return self
     */
    public function setEmailRules(): self
    {
        $this->validationRules['email'] = [
            new Assert\NotBlank(),
            new Assert\Email(),
        ];

        return $this;
    }

    /**
     * Set the validation rules for the name field
     *
     * @return self
     */
    public function setNameRules(): self
    {
        $this->validationRules['name'] = [
            new Assert\NotBlank(),
            new Assert\Regex([
                'pattern' => '/^[a-zA-Z ]+$/',
                'message' => 'Name must contain only alphabets and spaces'
            ]),
        ];

        return $this;
    }

    /**
     * Set the validation rules for the status field
     *
     * @return self
     */
    public function setStatusRules(): self
    {
        $this->validationRules['status'] = [
            new Assert\Choice(['choices' => ['active', 'inactive']])
        ];

        return $this;
    }

    /**
     * Set the validation rules for the name field
     *
     * @return self
     */
    public function setOptionalLastNameRules(): self
    {
        // If the last_name field is not set, return the current instance
        if (! isset($this->inputData['last_name'])) {
            return $this;
        }

        $this->validationRules['last_name'] = [
            new Assert\NotBlank(),
            new Assert\Regex([
                'pattern' => '/^[a-zA-Z ]+$/',
                'message' => 'Name must contain only alphabets and spaces'
            ]),
        ];

        return $this;
    }

    /**
     * Validate the input data
     *
     * @return self       The current instance
     * @throws \Respect\Validation\Exceptions\ValidationException
     */
    public function validate(): self
    {
        $validator  = Validation::createValidator();
        $violations = $validator->validate(
            $this->inputData,
            new Assert\Collection($this->validationRules)
        );

        if (0 !== count($violations)) {
            $this->validationPassed = false;

            foreach ($violations as $violation) {
                $this->validationErrors[] = $violation->getPropertyPath() . ': ' . $violation->getMessage();
            }
        } else {
            $this->validationPassed = true;
        }

        return $this;
    }

    /**
     * Get the input data
     *
     * @return array|null
     */
    public function getValidatedData(): ?array
    {
        return $this->inputData;
    }

    /**
     * Get the validation status
     *
     * @return bool
     */
    public function hasValidationPassed(): bool
    {
        return $this->validationPassed;
    }

    /**
     * Get the validation errors
     *
     * @return array
     */
    public function getValidationErrors(): array
    {
        return $this->validationErrors;
    }
}
