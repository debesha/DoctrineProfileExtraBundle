<?php
    /**
     * LoggingHydratorTrait is used to redefine function of inherited method hydrateAll()
     * See LoggingHydratorTrait
     *
     * For the full copyright and license information, please view the LICENSE
     * file that was distributed with this source code.
     *
     * Author: Dmytry Malyshenko (dmitry@malyshenko.com)
     * Date: 16.07.2015
     * Time: 12:48
     */

    namespace Debesha\DoctrineProfileExtraBundle\ORM;

    class LoggingObjectHydrator extends \Doctrine\ORM\Internal\Hydration\ObjectHydrator {

        use LoggingHydratorTrait;
    }