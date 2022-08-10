<?php

namespace App\Validator;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueEntityValueValidator extends ConstraintValidator
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @inheritDoc
     */
    public function validate(mixed $value, Constraint $constraint)
    {
        if (!$constraint instanceof UniqueEntityValue) {
            throw new UnexpectedTypeException($constraint, UniqueEntityValue::class);
        }

        $repository = $this->entityManager->getRepository($constraint->entityClass);

        $findValue = $repository->findBy([$constraint->columnName => $value]);

        if ($value !== null) {
            if (count($findValue) > 0) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ value }}', $value)
                    ->addViolation();
            }
        }
    }
}