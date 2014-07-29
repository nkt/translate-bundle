Translate bundle
================

Bundle provides translation mechanism for Doctrine entities. 

Installation
------------

Add to your `composer.json`:

```json
"require": {
    "nkt/translate-bundle": "~0.1"
},
"minimum-stability": "dev",
"prefer-stable": true
```

Enable the bundle in the kernel:

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Nkt\TranslateBundle\NktTranslateBundle(),
    );
}
```

Usage
-----

Let's create entities for simple blog.

```php
<?php

namespace Acme\BlogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Nkt\TranslateBundle\Behavior as TranslateBehavior;

class Post implements TranslateBehavior\LocaleAware
{
    use TranslateBehavior\Translatable;
    
    /**
     * @var int
     */
    private $id;
    
    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }
    
    public function getId()
    {
        return $this->id;
    }
}
```

Now we have to create entity for translations.

```php
<?php

namespace Acme\BlogBundle\Entity;

use Nkt\TranslateBundle\Behavior as TranslateBehavior;

class PostTranslation
{
    use TranslateBehavior\Translation;
    
    private $title;
    private $content;

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }
}
```

Now you can use it.

```php
public function newPostAction(Request $request)
{
    $post = new Post();
    foreach ($request->request->get('translations') as $translation) {
        $post->translate($translation['locale'])
             ->setTitle($translation['title'])
             ->setContent($translation['content']);
    }
    ...
}
```

The `translate` method look for translation for given `$locale`
and creates it if not found.

The `getTranslation` method look for translation for given `$locale`
and if not found, look for `currentLocale` translation.

LocaleAware interface
---------------------

If you want inject application locale in your translatable entities,
you should implement `Nkt\TranslateBundle\Behavior\LocaleAware` interface.
By default `currentLocale` equals to `en` in every translatable entity.

License
-------
MIT
