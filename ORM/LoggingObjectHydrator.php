<?php
/**
 * Author: Dmytry Malyshenko (dmitry@malyshenko.com)
 * Date: 16.07.2015
 * Time: 12:48
 */

namespace Debesha\DoctrineProfileExtraBundle\ORM;

class LoggingObjectHydrator extends \Doctrine\ORM\Internal\Hydration\ObjectHydrator {

    use LoggingHydratorTrait;
} 