<?php

/*
 * This file is part of the Kisaan package.
 *
 * (c) Cocolabs SAS <contact@cocolabs.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kisaan\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/*
 * Bookings imminent alert
 * Every 5 minutes
 */

//Cron: */5 * * * *  user   php app/console kisaan:bookings:alertImminent

class AlertImminentBookingsCommand extends ContainerAwareCommand
{

    public function configure()
    {
        $this
            ->setName('kisaan:bookings:alertImminent')
            ->setDescription('Alert imminent Bookings.')
            ->addOption(
                'delay',
                null,
                InputOption::VALUE_OPTIONAL,
                'Booking imminent alert delay in minutes. To use only on no prod env'
            )
            ->addOption(
                'test',
                null,
                InputOption::VALUE_NONE,
                'Extra precaution to ensure to use on test mode'
            )
            ->setHelp("Usage php bin/console kisaan:bookings:alertImminent");
    }

    /** @inheritdoc */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $delay = $this->getContainer()->getParameter('kisaan.booking.alert_imminent_delay');
        if ($input->getOption('test') && $input->getOption('delay')) {
            $delay = $input->getOption('delay');
        }

        $result = $this->getContainer()->get('kisaan.booking.manager')->alertImminentBookings($delay);
        $output->writeln($result . " booking(s) imminent alerted");
    }

}
