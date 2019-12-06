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
use Symfony\Component\Console\Output\OutputInterface;

/*
 * Calendar update alert commands
 * Every Month on 27  :
 */

//Cron: 0 0 27 * *  user   php app/console kisaan:listings:alertUpdateCalendars

class AlertListingsCalendarUpdateCommand extends ContainerAwareCommand
{
    /** @inheritdoc */
    public function configure()
    {
        $this
            ->setName('kisaan:listings:alertUpdateCalendars')
            ->setDescription('Alert listings calendars update.')
            ->setHelp("Usage php bin/console kisaan:listings:alertUpdateCalendars");
    }

    /** @inheritdoc */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $result = $this->getContainer()->get('kisaan.listing.manager')->alertUpdateCalendars();
        $output->writeln($result . " listing(s) calendar update alerted");
    }

}
