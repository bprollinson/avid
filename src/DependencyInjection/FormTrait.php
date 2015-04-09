<?php

namespace Avid\CandidateChallenge\DependencyInjection;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormTypeInterface;

/**
 * @property FormFactory $formFactory
 *
 * @author Kevin Archer <kevin.archer@avidlifemedia.com>
 */
trait FormTrait
{
    /**
     * @param string|FormTypeInterface $type
     * @param mixed $data
     * @param array $options
     *
     * @return Form
     */
    private function createForm($type, $data = null, array $options = array())
    {
        return $this->formFactory->create($type, $data, $options);
    }
}
