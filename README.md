# ShiftPHP

Welcome at ShiftPHP, a very simple event driven, MOVE oriented framework for PHP.

## Installation

Install the ShiftPHP framework using Composer:

    $ php composer.phar require shift/shift:0.*

## MOVE

ShiftPHP is MOVE oriented: Model, Operation, View and Events. To learn more
about this, see
"[MVC is dead, it's time to MOVE on](http://cirw.in/blog/time-to-move-on.html)".

## Usage

The core framework doesn't provide the things you would expect. It only
provides an Event Dispatcher and some facades for it. This means you can do
everything you want and you can use everything you want. The standard Shift
Edition provide integration with some Symfony components, but you can also use
it with other tools.

The main facade is ``Event``, the three others (``Operation``, ``Model`` and
``Application``) provides shortcuts for this facade.

### Application

Make sure you always boot your application before using it, booting it makes
sure the Event Dispatcher is added to the ``Event`` facade. To boot it, use
``Application::boot()``:

    use Wj\Shift\Facade\Application;

    Application::boot();

### Event

Use ``Event::on($event_name)`` to attach a listener to an event. This returns
an ``AttachingEvent``, on which you must set the target (using ``->asA`` /
``->asAn``) and the listeners (using ``->call``). A target is the context of
the event, for instance a class name or class type. The listener can be any
kind of callable:

    use Wj\Shift\Facade\Event;

    Event::on('some_event')->forA('the_target')->call(function ($event) {
        // ... do something nice
    });

Use ``Event::trigger($event_name)`` to trigger an event, this will call all
its listeners. This method returns a ``TriggeringEvent``, on which you must
set the target (``->forA`` / ``->forAn``) and the event class (can be ``null``):

    use Wj\Shift\Facade\Event;

    $event = new SomeEvent();
    // ... set up event class

    Event::trigger('some_event')->forA('the_target')->with($event);

### Operation

Operations are executed when a certain event is called, use
``Operation::on()`` to attach an operation to an event. This returns an
``AttachingEvent`` which has set the target to ``operation``. You are able to
change this.

    use Wj\Shift\Facade\Operation;

    Operation::on('some_event')->call(function ($event) {
        // ... perform an operation
    });

### Model

Models also listen to events and can be attached using ``Model::on()``. This
returns an ``AttachingEvent`` with the target set to ``model``.

    use Wj\Shift\Facade\Model;

    Model::on('some_event')->call(function ($event) {
        // ... do some modelish things
    });

### View

Views also listen to events and can be attached using ``View::on()``. This
returns an ``AttachingEvent`` with the target set to ``view``.

    View::on('some_event')->call(function ($event) {
        // ... render view
    });

### Operators

Operators are classes which holds multiple operations. Operator classes
implement ``Wj\Shift\Operator\OperatorInterface``. This requires a static
method called `getOperations` which returns an array of methods and events to
listen to:

    namespace Acme\Operator;

    use Wj\Shift\Operator\OperatorInterface;

    class UserOperator implements OperatorInterface
    {
        public static function getOperations()
        {
            return array(
                // key is the event and value is the method
                'save_user' => 'onSaveUser',
                // you can also attach multiple methods to one event
                'find_user' => array('onFindUser', 'onSecondFindUser'),
            );
        }
    }

After this, you can register the Operator using ``Operation::add()``:

    Operation::add('Acme\Operator\UserOperator');

## Bundles

Operators can also be grouped/bundled into bundles. This means you can easily activate
multiple bundles. A bundle must implement ``Wj\Shift\Bundle\BundleInterface``. This
requires one method called ``getOperators`` which must return a list of classnames.

ShiftPHP provides a basic ``Bundle`` class, which searches for ``*Operator.php`` files
in the root of the bundle and checks if the class in there implements the ``OperatorInterface``.

A common bundle looks like:

    namespace Amce\Bundle\DemoBundle;

    use Wj\Shift\Bundle\Bundle;

    class AcmeDemoBundle implements Bundle
    { }

To register a bundle, use ``Application:registerBundle()``:

    Application::registerBundle(new \Acme\Bundle\DemoBundle\AcmeDemoBundle());

## Contributing

A framework can only get good with the contributions of other people. Issues,
discussions and pull requests are very welcome! There are not many rules, as
long as you stick to the 
[Symfony Standards](http://symfony.com/doc/current/contributing/code/standards.html).

## License

The Shift framework is licensed under the MIT license.
