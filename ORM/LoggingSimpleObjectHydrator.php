<?php
/**
 * Author: Dmytry Malyshenko (dmitry@malyshenko.com)
 * Date: 16.07.2015
 * Time: 19:38
 */

namespace Debesha\DoctrineProfileExtraBundle\ORM;

use Doctrine\ORM\Internal\Hydration\SimpleObjectHydrator;

class LoggingSimpleObjectHydrator extends SimpleObjectHydrator {

    use LoggingHydratorTrait;

}