<?php

namespace Nkt\TranslateBundle\Behavior;

/**
 * @author Nikita Gusakov <dev@nkt.me>
 */
interface LocaleAware
{
    /**
     * @param string $locale
     */
    public function setCurrentLocale($locale);
}
