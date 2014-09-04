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
     * @return Translation
     */
    public static function createTranslation()
    {
        $class = __CLASS__ . 'Translation';

        return new $class;
    }

    /**
     * @param string $locale
     *
     * @return Translation
     */
    public function translate($locale = null)
    {
        $searchLocale = $locale === null ? $this->getCurrentLocale() : $locale;
        $translations = $this->getTranslations();
        if (!$translations->containsKey($searchLocale)) {
            $searchLocale = $this->getDefaultLocale();
        }
        $translation = $translations->get($searchLocale);
        if ($translation === null) {
            $translation = static::createTranslation();
            $translation->setLocale($locale);
        }

        return $translation;
    }

    /**
     * @param string $locale
     *
     * @return Translation
     */
    public function updateTranslation($locale)
    {
        $translations = $this->getTranslations();
        if ($translations->containsKey($locale)) {
            return $translations->get($locale);
        }
        $translation = static::createTranslation();
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
     * @return string
     */
    public function getCurrentLocale()
    {
        return $this->currentLocale;
    }

    /**
     * @param string $currentLocale
     */
    public function setCurrentLocale($currentLocale)
    {
        $this->currentLocale = $currentLocale;
    }

    /**
     * @return string
     */
    public function getDefaultLocale()
    {
        return $this->defaultLocale;
    }

    /**
     * @param string $defaultLocale
     */
    public function setDefaultLocale($defaultLocale)
    {
        $this->defaultLocale = $defaultLocale;
    }
}
