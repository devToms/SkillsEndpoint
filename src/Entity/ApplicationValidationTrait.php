<?php

namespace App\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

trait ApplicationValidationTrait
{
    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        $metadata->addConstraint(new UniqueEntity([
            'fields' => 'phoneNumber'
        ]));

        $metadata->addConstraint(new UniqueEntity([
            'fields' => 'email'
        ]));

        $metadata->addPropertyConstraint('email', new Assert\Email());
    }
}
