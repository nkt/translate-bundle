<?php

namespace Nkt\TranslateBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Nkt\TranslateBundle\Behavior\LocaleAware;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @author Nikita Gusakov <dev@nkt.me>
 */
class LocaleEventSubscriber implements EventSubscriber
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Inject Translator locale into loaded object.
     *
     * @param LifecycleEventArgs $args
     */
    public function postLoad(LifecycleEventArgs $args)
    {
        $object = $args->getObject();
        if ($object instanceof LocaleAware) {
            $object->setCurrentLocale($this->translator->getLocale());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return [Events::postLoad];
    }
}
