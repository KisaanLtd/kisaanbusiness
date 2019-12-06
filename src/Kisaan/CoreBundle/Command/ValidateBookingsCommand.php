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
 * Validate bookings commands
 * For example every hour :
 */

//Cron: 0 */1  * * *  user   php app/console kisaan:bookings:validate

class ValidateBookingsCommand extends ContainerAwareCommand
{

    public function configure()
    {
        $this
            ->setName('kisaan:bookings:validate')
            ->setDescription('Validate Bookings.')
            ->addOption(
                'delay',
                null,
                InputOption::VALUE_OPTIONAL,
                'Booking validation delay in minutes. To use only on no prod env'
            )
            ->addOption(
                'moment',
                null,
                InputOption::VALUE_OPTIONAL,
                'Booking validation moment. To use only on no prod env'
            )
            ->addOption(
                'test',
                null,
                InputOption::VALUE_NONE,
                'Extra precaution to ensure to use on test mode'
            )
            ->setHelp("Usage php bin/console kisaan:bookings:validate");
    }

    /** @inheritdoc */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $moment = $this->getContainer()->getParameter('kisaan.booking.validated_moment');
        $delay = $this->getContainer()->getParameter('kisaan.booking.validated_delay');
        if ($input->getOption('test') && $input->getOption('delay') && $input->getOption('moment')) {
            $moment = $input->getOption('moment');
            $delay = $input->getOption('delay');
        }

        $result = $this->getContainer()->get('kisaan.booking.manager')->validateBookings($moment, $delay);
        $output->writeln($result . " booking(s) validated");
    }

}
