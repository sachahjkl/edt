<?php

namespace App\Twig;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class ParamExtension extends AbstractExtension
{

    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_param', [$this, 'getParameter']),
        ];
    }

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    public function getParameter($value)
    {
        return $this->parameterBag->get($value);
    }
}
