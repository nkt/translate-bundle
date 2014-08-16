<?php

namespace Nkt\TranslateBundle\Behavior;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @author Nikita Gusakov <dev@nkt.me>
 */
trait Translatable
{
    /**
     * @var string
     */
    protected $defaultLocale = 'en';
    /**
     * @var string
     */
    protected $currentLocale = 'en';
    /**
     * @var Translation[]|ArrayCollection
     */
    protected $translations;

    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }

    /**
     * @param string $locale
     *
     * @return Translation
     */
    public function translate($locale = null)
    {
        if ($locale === null) {
            $locale = $this->getCurrentLocale();
        }
        $translations = $this->getTranslations();
        if (!$translations->containsKey($locale)) {
            $locale = $this->getDefaultLocale();
        }

        return $translations->get($locale);
    }

    public function updateTranslation($locale)
    {
        $translations = $this->getTranslations();
        if ($translations->containsKey($locale)) {
            return $translations->get($locale);
        }
        $translation = static::createNewTranslation();
        $translation->setLocale($locale);
        $this->addTranslation($translation);

        return $translation;
    }

    /**
     * @return Translation[]|ArrayCollection
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * @return array
     */
    public function getAvailableLocales()
    {
        return $this->getTranslations()->getKeys();
    }

    /**
     * @param Translation $translation
     *
     * @return static
     */
    public function addTranslation($translation)
    {
        $translation->setTranslatable($this);
        $this->translations->set($translation->getLocale(), $translation);

        return $this;
    }

    /**
     * @param Translation $translation
     *
     * @return static
     */
    public function removeTranslation($translation)
    {
        $this->translations->removeElement($translation);

        return $this;
    }

    /**
     * @param string $locale
     *
     * @return static
     */
    public function removeTranslationByLocale($locale)
    {
        $this->translations->remove($locale);

        return $this;
    }

    /**
     * @return Translation
     */
    public static function createNewTranslation()
    {
        $class = __CLASS__ . 'Translation';

        return new $class;
    }

    /**
     * @return string
     */
    public function getCurrentLocale()
    {
        return $this->currentLocale;
    }

    /**
     * @param string $currentLocale
     *
     * @return static
     */
    public function setCurrentLocale($currentLocale)
    {
        $this->currentLocale = $currentLocale;

        return $this;
    }

    /**
     * @param string $defaultLocale
     *
     * @return static
     */
    public function setDefaultLocale($defaultLocale)
    {
        $this->defaultLocale = $defaultLocale;

        return $this;
    }

    /**
     * @return string
     */
    public function getDefaultLocale()
    {
        return $this->defaultLocale;
    }
}
