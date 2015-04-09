<?php

namespace Avid\CandidateChallenge\Form;

use Avid\CandidateChallenge\Form\Data\MemberData;
use Avid\CandidateChallenge\Model\BodyType;
use Avid\CandidateChallenge\Model\Ethnicity;
use Avid\CandidateChallenge\Model\Limits;
use Avid\CandidateChallenge\Validator\Constraints\Age;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Kevin Archer <kevin.archer@avidlifemedia.com>
 */
class MemberType extends AbstractType
{
    const NAME = 'member';

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', 'text', ['constraints' => new NotBlank()])
            ->add('password', 'text', ['constraints' => new NotBlank()])
            ->add('city', 'text', ['constraints' => new NotBlank()])
            ->add('postalCode', 'text', [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 6, 'max' => 7]),
                ],
            ])
            ->add('dateOfBirth', 'birthday', [
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank(),
                    new Date(),
                    new Age(['min' => 18])],
            ])
            ->add('limits', 'choice', [
                'constraints' => new NotBlank(),
                'choices' => Limits::all(),
            ])
            ->add('height', 'text', [
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 5]),
                ],
            ])
            ->add('weight', 'text', [
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 7]),
                ],
            ])
            ->add('bodyType', 'choice', [
                'constraints' => new NotBlank(),
                'choices' => BodyType::all(),
            ])
            ->add('ethnicity', 'choice', [
                'constraints' => new NotBlank(),
                'choices' => Ethnicity::all(),
            ])
            ->add('email', 'email', [
                'constraints' => [
                    new NotBlank(),
                    new Email(),
                ],
            ])
            ->add('Save', 'submit');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MemberData::CLASS_NAME
        ]);
    }

    /**
     * @return string The name of this type
     */
    public function getName()
    {
        return self::NAME;
    }
}
