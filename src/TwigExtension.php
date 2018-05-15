<?php

namespace Tesonet\ReactJsTwig;

use ReactJS;

use Twig_Extension;
use Twig_SimpleFunction;
use Twig_LoaderInterface;

class TwigExtension extends Twig_Extension
{
    /**
     * @var Callable
     */
    private $errorHandler;

    /**
     * @var Twig_LoaderInterface
     */
    private $loader;

    public function __construct()
    {
        $this->errorHandler = function ($exception) {
            throw $exception;
        };
    }

    public function getName(): string
    {
        return 'react-js-twig';
    }

    public function getFunctions(): array
    {
        return [
            new Twig_SimpleFunction(
                'reactGenerateMarkup',
                [$this, 'reactGenerateMarkup'],
                ['is_safe' => ['html']]
            ),
            new Twig_SimpleFunction(
                'reactGenerateJavascript',
                [$this, 'reactGenerateJavascript'],
                ['is_safe' => ['html']]
            ),
        ];
    }

    public function reactGenerateMarkup(array $reactConfiguration): string
    {
        $reactJs = $this->createReactJs($reactConfiguration['sourcePath']);
        $reactJs->setComponent($reactConfiguration['componentName'], $reactConfiguration['props']);
        return $reactJs->getMarkup();
    }

    public function reactGenerateJavascript(array $reactConfiguration): string
    {
        $reactJs = $this->createReactJs($reactConfiguration['sourcePath']);
        $reactJs->setComponent($reactConfiguration['componentName'], $reactConfiguration['props']);
        return $reactJs->getJS($reactConfiguration['where']);
    }

    private function createReactJs(string $sourcePath): ReactJS
    {
        $reactSource = $this->loader->getSourceContext($sourcePath)->getCode();
        $reactJs = new ReactJS($reactSource, '');
        $reactJs->setErrorHandler($this->errorHandler);
        return $reactJs;
    }

    public function setErrorHandler(callable $errorHandler): self
    {
        $this->errorHandler = $errorHandler;

        return $this;
    }

    public function setLoader(Twig_LoaderInterface $loader): self
    {
        $this->loader = $loader;

        return $this;
    }
}
