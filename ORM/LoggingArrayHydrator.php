<?php
/**
 * Author: Dmytry Malyshenko (dmitry@malyshenko.com)
 * Date: 16.07.2015
 * Time: 12:50
 */

namespace Debesha\DoctrineProfileExtraBundle\ORM;


use Doctrine\ORM\Internal\Hydration\ArrayHydrator;

class LoggingArrayHydrator extends ArrayHydrator {

    use LoggingHydratorTrait;
} 