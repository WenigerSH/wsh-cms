<?php
namespace Wsh\CmsBundle\Helper;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * The dry up trait for all entities using the translations
 */
trait Translatable
{
    /**
     * Used locale to override Translation listener`s locale
     * this is not a mapped field of entity metadata, just a simple property
     *
     * @Gedmo\Locale
     */
    protected $locale;

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * Set translations
     *
     * @param ArrayCollection $translations
     */
    public function setTranslations($translations)
    {
        $this->translations = $translations;
    }

    /**
     * Get translations
     *
     * @return ArrayCollection
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * Add translation
     *
     * @param AbstractPersonalTranslation
     */
    public function addTranslation(AbstractPersonalTranslation $translation)
    {
        if ($translation->getContent()) {
            $translation->setObject($this);
            $this->translations->add($translation);
        }
    }

    /**
     * Remove translation
     *
     * @param AbstractPersonalTranslation
     */
    public function removeTranslation(AbstractPersonalTranslation $translation)
    {
        $this->translations->removeElement($translation);
    }
}
