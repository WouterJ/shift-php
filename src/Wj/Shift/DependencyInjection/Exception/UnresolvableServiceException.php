<?php

/*
 * This file is part of the ShiftPHP package.
 *
 * (c) 2013 Wouter de Jong
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Wj\Shift\DependencyInjection\Exception;

class UnresolvableServiceException extends ContainerException
{
    public function __construct($class, $parameterName, $message, $code = 0, \Exception $previous = null)
    {
        parent::__construct(
            sprintf(
                'Unresolvable service: "%s"; Cannot resolve parameter "$%s": %s',
                $class,
                $parameterName,
                $message
            ),
            $code,
            $previous
        );
    }
}
