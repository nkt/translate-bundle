<?php

namespace Nkt\TranslateBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * @author Nikita Gusakov <dev@nkt.me>
 */
class SchemaEventSubscriber implements EventSubscriber
{
    /**
     * @var string
     */
    private $translatableTrait;
    /**
     * @var string
     */
    private $translationTrait;
    /**
     * @var string
     */
    private $translatableFetchMode;
    /**
     * @var string
     */
    private $translationFetchMode;

    public function __construct($translatableTrait, $translationTrait, $translatableFetchMode, $translationFetchMode)
    {
        $this->translatableTrait = $translatableTrait;
        $this->translationTrait = $translationTrait;
        $this->translatableFetchMode = $this->normalizeFetchMode($translatableFetchMode);
        $this->translationFetchMode = $this->normalizeFetchMode($translationFetchMode);
    }

    /**
     * @param LoadClassMetadataEventArgs $args
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $args)
    {
        $metadata = $args->getClassMetadata();
        $reflection = $metadata->getReflectionClass();
        if ($reflection === null) {
            return; // fix for crud generation wtf
        }
        if ($this->hasTrait($reflection, $this->translatableTrait)) {
            $this->updateTranslatableMetadata($metadata);
        } elseif ($this->hasTrait($reflection, $this->translationTrait)) {
            $this->updateTranslationMetadata($metadata);
        }
    }

    protected function updateTranslatableMetadata(ClassMetadata $metadata)
    {
        if (!$metadata->hasAssociation('translations')) {
            $metadata->mapOneToMany([
                'fieldName'     => 'translations',
                'mappedBy'      => 'translatable',
                'indexBy'       => 'locale',
                'cascade'       => ['persist', 'merge', 'remove'],
                'fetch'         => $this->translatableFetchMode,
                'targetEntity'  => $metadata->name . 'Translation',
                'orphanRemoval' => true
            ]);
        }
    }

    protected function updateTranslationMetadata(ClassMetadata $metadata)
    {
        if (!$metadata->hasAssociation('translatable')) {
            $metadata->mapManyToOne([
                'fieldName'    => 'translatable',
                'inversedBy'   => 'translations',
                'fetch'        => $this->translationFetchMode,
                'joinColumns'  => [[
                    'name'                 => 'translatable_id',
                    'referencedColumnName' => 'id',
                    'onDelete'             => 'CASCADE'
                ]],
                'targetEntity' => substr($metadata->name, 0, -strlen('Translation'))
            ]);
            $metadata->table['uniqueConstraints'][] = [
                'name'    => 'unique_translation',
                'columns' => ['translatable_id', 'locale']
            ];
        }
    }

    protected function normalizeFetchMode($mode)
    {
        if (is_int($mode)) {
            return $mode;
        }
        switch (strtolower($mode)) {
            case 'eager':
                return ClassMetadata::FETCH_EAGER;
            case 'extra_lazy':
                return ClassMetadata::FETCH_EXTRA_LAZY;
            default:
                return ClassMetadata::FETCH_LAZY;
        }
    }

    /**
     * @param \ReflectionClass $class
     * @param string           $trait
     *
     * @return bool
     */
    protected function hasTrait(\ReflectionClass $class, $trait)
    {
        if (in_array($trait, $class->getTraitNames())) {
            return true;
        }
        if (false === $parent = $class->getParentClass()) {
            return false;
        }

        return $this->hasTrait($parent, $trait);
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return [Events::loadClassMetadata];
    }
}
