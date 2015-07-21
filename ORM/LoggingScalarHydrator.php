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
     * Time: 12:51
     */

    namespace Debesha\DoctrineProfileExtraBundle\ORM;

    use Doctrine\ORM\Internal\Hydration\ScalarHydrator;

    class LoggingScalarHydrator extends ScalarHydrator
    {

        use LoggingHydratorTrait;
    }