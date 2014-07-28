<?php

namespace Nkt\TranslateBundle\Tests\Behavior;

use Nkt\TranslateBundle\Tests\Entity\Post;

/**
 * @author Nikita Gusakov <dev@nkt.me>
 */
class TranslatableTest extends \PHPUnit_Framework_TestCase
{
    public function testTranslate()
    {
        $post = new Post();
        $translation = $post->translate('ru');

        $this->assertSame($post->translate('ru'), $translation);
    }

    public function testGetTranslation()
    {
        $post = new Post();
        $translation = $post->translate($post->getDefaultLocale());

        $this->assertSame($translation, $post->getTranslation('ru'));

        $russian = $post->translate('ru');

        $this->assertSame($russian, $post->getTranslation('ru'));
    }
}
