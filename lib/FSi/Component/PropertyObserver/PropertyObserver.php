<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\PropertyObserver;

use Symfony\Component\PropertyAccess\PropertyAccess;

class PropertyObserver implements PropertyObserverInterface
{
    /**
     * Internal value storage
     *
     * @var array
     */
    protected $savedValues = array();

    /**
     * {@inheritdoc}
     */
    public function saveValue($object, $propertyPath)
    {
        if (!is_object($object)) {
            throw new \InvalidArgumentException('Only object\'s properties could be observed by PropertyObserver');
        }

        $oid = spl_object_hash($object);
        if (!isset($this->savedValues[$oid])) {
            $this->savedValues[$oid] = array();
        }
        $this->savedValues[$oid][$propertyPath] = PropertyAccess::getPropertyAccessor()->getValue($object, $propertyPath);
    }

    /**
     * {@inheritdoc}
     */
    public function getSavedValue($object, $propertyPath)
    {
        if (!is_object($object)) {
            throw new \InvalidArgumentException('Only object\'s properties could be observed by PropertyObserver');
        }

        $oid = spl_object_hash($object);
        if (!isset($this->savedValues[$oid]) || !array_key_exists($propertyPath, $this->savedValues[$oid])) {
            throw new \BadMethodCallException(sprintf('Value of property "%s" from specified object was not saved previously', $propertyPath));
        }

        return $this->savedValues[$oid][$propertyPath];
    }

    /**
     * {@inheritdoc}
     */
    public function resetValue($object, $propertyPath)
    {
        PropertyAccess::getPropertyAccessor()->setValue(
            $object,
            $propertyPath,
            $this->getSavedValue($object, $propertyPath)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function hasValueChanged($object, $propertyPath)
    {
        if (!is_object($object)) {
            throw new \InvalidArgumentException('Only object\'s properties could be observed by PropertyObserver');
        }

        return ($this->getSavedValue($object, $propertyPath) !== PropertyAccess::getPropertyAccessor()->getValue($object, $propertyPath));
    }
}
