<?php

namespace Nkt\TranslateBundle\Tests\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Nkt\TranslateBundle\Behavior\Translatable;

/**
 * @author Nikita Gusakov <dev@nkt.me>
 */
class Post
{
    use Translatable;

    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }
}
