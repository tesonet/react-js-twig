# ReactJs Twig

[![Latest Stable Version](https://poser.pugx.org/tesonet/react-js-twig/v/stable)](https://packagist.org/packages/tesonet/react-js-twig)
[![Total Downloads](https://poser.pugx.org/tesonet/react-js-twig/downloads)](https://packagist.org/packages/tesonet/react-js-twig)
[![License](https://poser.pugx.org/tesonet/react-js-twig/license)](https://packagist.org/packages/tesonet/react-js-twig)

This library simplifies server rendering using ReactJs and Twig.

## Prerequisites
This library does server rendering using the v8js php extension.
So in order to use this library you have to [install v8js](https://github.com/phpv8/v8js/blob/master/README.Linux.md) 

## Dependencies
- Composer
- PHP >= 5.6
- V8Js php extension

## Usage

### 1) Run `composer require tesonet/react-js-twig`

### 2) Create the extension and add it to twig
 
```php
use Tesonet\ReactJsTwig\TwigExtension;

// create the extension
$reactExtension = new TwigExtension();

// add it to twig
$twig->addExtension($reactExtension);

// allow access to the filesystem loader
$reactExtension->setLoader($view->getLoader());
```

### 3) Setting the error handler (optional)
 
By default any errors encountered during server rendering are re-thrown.
Sometimes, you want to debug the error in the browser instead.

To do this, override the default error handler like so:

```php
$reactExtension = new TwigExtension();
$reactExtension->setErrorHandler(function () {
  // do nothing
});
```

### 4) Use it in your template

```twig
{% set reactConfiguration = {
    'sourcePath': './path/to/assets/file.js',
    'componentName': 'MyComponentName',
    'props': { 'name': 'My prop has a name!' },
    'where': '#container'
} %}

{% spaceless %}
    <div id="{{ reactConfiguration.where | slice(1) }}">
        {{ reactGenerateMarkup(reactConfiguration) }}
    </div>
{% endspaceless %}

<script src="/server/url/of/assets/file.js"></script>
<script>{{ reactGenerateJavascript(reactConfiguration) }}</script>
```

### 5) Use caching (optional)

If you wish to cache the generated markup, we highly recommend the [asm89/twig-cache-extension](https://github.com/asm89/twig-cache-extension) package.

Once you add the previously mentioned cache extension, wrap the `reactGenerateMarkup` call inside the cache block like so:

```twig
{% set reactConfiguration = {
    'sourcePath': './path/to/assets/file.js',
    'componentName': 'MyComponentName',
    'props': { 'name': 'My prop has a name!' },
    'where': '#container'
} %}

{% spaceless %}
    <div id="{{ reactConfiguration.where | slice(1) }}">
        {% cache 'markup' reactConfiguration %}
            {{ reactGenerateMarkup(reactConfiguration) }}
        {% endcache %}
    </div>
{% endspaceless %}

<script src="/server/url/of/assets/file.js"></script>
<script>{{ reactGenerateJavascript(reactConfiguration) }}</script>
```
