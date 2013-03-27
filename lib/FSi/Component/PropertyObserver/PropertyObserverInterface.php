<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\PropertyObserver;

interface PropertyObserverInterface
{
    /**
     * Saves current value of specified property in internal storage.
     *
     * @param object $object
     * @param string $propertyPath
     * @throws \InvalidArgumentException
     */
    public function saveValue($object, $propertyPath);

    /**
     * Returns previously saved value of specified property or throws exception if it has not been saved.
     *
     * @param object $object
     * @param string $propertyPath
     * @throws \InvalidArgumentException
     * @throws \BadMethodCallException
     * @return mixed
     */
    public function getSavedValue($object, $propertyPath);

    /**
     * Sets previously saved value of specified property or throws exception if it has not been saved.
     *
     * @param object $object
     * @param string $propertyPath
     */
    public function resetValue($object, $propertyPath);

    /**
     * Returns true if previously saved value is different (in PHP strict sense) from the current one.
     *
     * @param object $object
     * @param string $propertyPath
     * @throws \InvalidArgumentException
     * @return boolean
     */
    public function hasValueChanged($object, $propertyPath);
}
