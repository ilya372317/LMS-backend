<?php

namespace App\Validator;

use Attribute;
use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class UniqueEntityValue extends Constraint
{
    /**
     * Entity class string.
     *
     * @var string
     */
    public string $entityClass;

    public string $columnName;

    public string $message;

    #[HasNamedArguments]
    public function __construct(
        string $entity,
        string $columnName,
        string $message = '{{ value }} already exists',
        mixed $options = null,
        array $groups = null,
        mixed $payload = null
    ) {
        parent::__construct($options, $groups, $payload);
        $this->entityClass = $entity;
        $this->columnName = $columnName;
        $this->message = $message;
    }
}