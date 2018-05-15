<?php
namespace Tesonet\ReactJsTwig;

use ReactJS;

class TwigExtension extends \Twig_Extension
{
    /**
     * @var Callable
     */
    private $errorHandler;

    /**
     * @var \Twig_LoaderInterface
     */
    private $loader;

    public function __construct()
    {
        $this->errorHandler = function ($exception) {
            throw $exception;
        };
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'react-js-twig';
    }

    /**
     * @inheritdoc
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('reactGenerateMarkup', [$this, 'reactGenerateMarkup'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction(
                'reactGenerateJavascript',
                [$this, 'reactGenerateJavascript'],
                ['is_safe' => ['html']]
            ),
        ];
    }

    /**
     * @param array $reactConfiguration
     * @return string
     */
    public function reactGenerateMarkup($reactConfiguration)
    {
        $reactJs = $this->createReactJs($reactConfiguration['sourcePath']);
        $reactJs->setComponent($reactConfiguration['componentName'], $reactConfiguration['props']);
        return $reactJs->getMarkup();
    }

    /**
     * @param array $reactConfiguration
     * @return string
     */
    public function reactGenerateJavascript($reactConfiguration)
    {
        $reactJs = $this->createReactJs($reactConfiguration['sourcePath']);
        $reactJs->setComponent($reactConfiguration['componentName'], $reactConfiguration['props']);
        return $reactJs->getJS($reactConfiguration['where']);
    }

    /**
     * @param string $sourcePath
     * @return ReactJS
     *
     * @throws \Exception
     */
    private function createReactJs($sourcePath)
    {
        $reactSource = $this->loader->getSourceContext($sourcePath)->getCode();
        $reactJs = new ReactJS($reactSource, '');
        $reactJs->setErrorHandler($this->errorHandler);
        return $reactJs;
    }

    /**
     * @param Callable $errorHandler
     */
    public function setErrorHandler($errorHandler)
    {
        $this->errorHandler = $errorHandler;
    }

    /**
     * @param \Twig_LoaderInterface $loader
     */
    public function setLoader($loader)
    {
        $this->loader = $loader;
    }
}
