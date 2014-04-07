# Usage examples #

## Basic usage with single property ##

Let's assume we have some ``$object`` and we want to check if value of its ``name`` property has changed between point A
and point B in our code.

```php
use FSi\Component\PropertyObserver\PropertyObserver

$object = new SomeObjectClass();
$object->setName('some name');

// point A
$observer = new PropertyObserver();
$observer->saveValue($object, 'name');

$api = new SomeApiClient();
$object->setName($api->getName());

// point B
if ($observer->hasChangedValue($object, 'name')) {
    echo sprintf(
        'Name has changed from "%s" to "%s"',
         $observer->getSavedValue($object, 'name'),
         $object->getName()
    );
}
```

The whole magic behind mapping property names to getters/setters (if necessary) is done by symfony/property-access under the hood.

## Using on multiple properties at once ##

Another way to observe object's property is to do it for several properties at one time.
In order to do that you need ``MultiplePropertyObserver``.


```php
use FSi\Component\PropertyObserver\MultiplePropertyObserver

$object = new SomeObjectClass();
$object->setName('some name');
$object->setDate(new \DateTime());
$object->setText('text');


// point A
$observer = new MultiplePropertyObserver();
$observedProperties = array('name', 'date', 'text');
$observer->saveValues($object, $observedProperties);

$api = new SomeApiClient();
$object->setName($api->getName());
$object->setText($api->getText($object->getId()));

// point B
if ($observer->hasChangedValues($object, $observedProperties)) {
    $object->setChanged(true);
}
```

## Clearing observer's state ##

If you want to use one ``PropertyObserver`` instance to handle very large number of objects, i.e in long running
scripts or batches, you will probably find method ``clear()`` useful.

```php
use FSi\Component\PropertyObserver\MultiplePropertyObserver

$observer = new MultiplePropertyObserver();
$observedProperties = array('name', 'date', 'text');
$api = new SomeApiClient();

foreach ($collection as $object) {
    $observer->saveValues($object, $observedProperties);

    $object->setName(
        $api->getName($object->getId())
    );
    $object->setText(
        $api->getText($object->getId())
    );

    if ($observer->hasChangedValues($object, $observedProperties)) {
        $object->setChanged(true);
    }

    $observer->clear();
}
```
