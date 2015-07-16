<?php
/**
 * Author: Dmytry Malyshenko (dmitry@malyshenko.com)
 * Date: 16.07.2015
 * Time: 19:37
 */

namespace Debesha\DoctrineProfileExtraBundle\ORM;


use Doctrine\ORM\Internal\Hydration\SingleScalarHydrator;

class LoggingSingleScalarHydrator extends SingleScalarHydrator {

    use LoggingHydratorTrait;

} 