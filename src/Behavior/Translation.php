<?php

namespace Nkt\TranslateBundle\Behavior;

/**
 * @author Nikita Gusakov <dev@nkt.me>
 */
trait Translation
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @var string
     * @ORM\Column(type="string", length=5)
     */
    protected $locale;
    /**
     * @var Translatable
     */
    protected $translatable;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param string $locale
     *
     * @return static
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @return Translatable
     */
    public function getTranslatable()
    {
        return $this->translatable;
    }

    /**
     * @param Translatable $translatable
     *
     * @return static
     */
    public function setTranslatable($translatable)
    {
        $this->translatable = $translatable;

        return $this;
    }
}
