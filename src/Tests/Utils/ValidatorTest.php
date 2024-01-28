<?php

namespace Tests\Utils;

use App\Utils\Validator;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    public function testValidator()
    {
        $validator = new Validator();

        $data = [
            'email' => 'test@example.com',
            'name' => 'Test User',
            'status' => 'active',
            'last_name' => 'User'
        ];

        $validator->setInputData($data)
            ->setEmailRules()
            ->setNameRules()
            ->setStatusRules()
            ->setOptionalLastNameRules()
            ->validate();

        $this->assertTrue($validator->hasValidationPassed());
        $this->assertEmpty($validator->getValidationErrors());
        $this->assertEquals($data, $validator->getValidatedData());
    }

    public function testValidatorWithInvalidData()
    {
        $validator = new Validator();

        $data = [
            'email' => 'invalid email',
            'name' => '123456', // name with numbers
            'status' => 'unknown', // invalid status
            'last_name' => '123456' // last name with numbers
        ];
        
        $validator->setInputData($data)
            ->setEmailRules()
            ->setNameRules()
            ->setStatusRules()
            ->setOptionalLastNameRules()
            ->validate();

        $this->assertFalse($validator->hasValidationPassed());
        $this->assertNotEmpty($validator->getValidationErrors());
    }
}
