<?php

namespace Avid\CandidateChallenge\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @author Kevin Archer <kevin.archer@avidlifemedia.com>
 */
final class AgeValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$value instanceof \DateTime) {
            return;
        }

        /**
         * @var Age $constraint
         * @var \DateTime $value
         */
        $now = new \DateTime();
        $age = $now->diff($value)->y;

        if ($age < $constraint->min) {
            $this->context->addViolation($constraint->minMessage, [
                '{{ min }}' => $constraint->min,
                '{{ age }}' => $age,
            ]);
        }
    }
}
