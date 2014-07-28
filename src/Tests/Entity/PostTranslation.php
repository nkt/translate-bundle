<?php

namespace Nkt\TranslateBundle\Tests\Entity;

use Nkt\TranslateBundle\Behavior\Translation;

/**
 * @author Nikita Gusakov <dev@nkt.me>
 */
class PostTranslation
{
    use Translation;

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
