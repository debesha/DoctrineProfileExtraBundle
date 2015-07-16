<?php
/**
 * Author: Dmytry Malyshenko (dmitry@malyshenko.com)
 * Date: 16.07.2015
 * Time: 12:51
 */

namespace Debesha\DoctrineProfileExtraBundle\ORM;

use Doctrine\ORM\Internal\Hydration\ScalarHydrator;

class LoggingScalarHydrator extends ScalarHydrator {

    use LoggingHydratorTrait;
} 