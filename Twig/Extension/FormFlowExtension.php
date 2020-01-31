<?php

namespace Craue\FormFlowBundle\Twig\Extension;

use Craue\FormFlowBundle\Form\FormFlow;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Twig extension for form flows.
 *
 * @author    Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2013 Christian Raue
 * @license   http://opensource.org/licenses/mit-license.php MIT License
 */
class FormFlowExtension extends AbstractExtension
{

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'craue_formflow';
    }

    /**
     * {@inheritDoc}
     */
    public function getFilters()
    {
        return [
            new TwigFilter(
                'craue_addDynamicStepNavigationParameter',
                [$this, 'addDynamicStepNavigationParameter']
            ),
            new TwigFilter(
                'craue_removeDynamicStepNavigationParameter',
                [$this, 'removeDynamicStepNavigationParameter']
            ),
        ];
    }

    /**
     * Adds the parameter for dynamic step navigation.
     *
     * @param array    $parameters Current route parameters.
     * @param FormFlow $flow       The flow involved.
     * @param integer  $step       The step the navigation link will be generated for.
     *
     * @return array Route parameters plus the step parameter.
     */
    public function addDynamicStepNavigationParameter(array $parameters, FormFlow $flow, $step)
    {
        $parameters[$flow->getDynamicStepNavigationParameter()] = $step;

        return $parameters;
    }

    /**
     * Removes the parameter for dynamic step navigation.
     *
     * @param array    $parameters Current route parameters.
     * @param FormFlow $flow       The flow involved.
     *
     * @return array Route parameters without the step parameter.
     */
    public function removeDynamicStepNavigationParameter(array $parameters, FormFlow $flow)
    {
        unset($parameters[$flow->getDynamicStepNavigationParameter()]);

        return $parameters;
    }

}
