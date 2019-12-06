<?php

use Kisaan\CoreBundle\Command\AlertExpiringBookingsCommand;
use Kisaan\CoreBundle\Command\AlertImminentBookingsCommand;
use Kisaan\CoreBundle\Command\AlertListingsCalendarUpdateCommand;
use Kisaan\CoreBundle\Command\CheckBookingsBankWiresCommand;
use Kisaan\CoreBundle\Command\CurrencyCommand;
use Kisaan\CoreBundle\Command\ExpireBookingsCommand;
use Kisaan\CoreBundle\Command\ValidateBookingsCommand;
use Kisaan\ListingSearchBundle\Command\ComputeListingsNotationCommand;
use Lexik\Bundle\CurrencyBundle\Command\ImportCurrencyCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class CommandContext extends CommonContext
{
    /** @var  CommandTester $tester */
    protected $tester;


    /**
     * @When /^(?:|I|System user) run "([^"]*)" command$/
     *
     * @param string $name
     */
    public function iRunCommand($name)
    {
        $application = new Application($this->kernel);
        $arguments = array();

        switch ($name) {
            case "kisaan:currency:update":
                $application->add(new CurrencyCommand());
                $application->add(new ImportCurrencyCommand());
                break;
            case "kisaan:bookings:expire":
                $application->add(new ExpireBookingsCommand());
                $arguments["--expiration-delay"] = 0;//kisaan.booking.expiration_delay
                $arguments["--acceptation-delay"] = 0;//kisaan.booking.acceptation_delay
                $arguments["--test"] = true;
                break;
            case "kisaan:bookings:alertExpiring":
                $application->add(new AlertExpiringBookingsCommand());
                $arguments["--alert-delay"] = 0;//kisaan.booking.alert_expiration_delay
                $arguments["--expiration-delay"] = 0;//kisaan.booking.expiration_delay
                $arguments["--acceptation-delay"] = 0;//kisaan.booking.acceptation_delay
                $arguments["--test"] = true;
                break;
            case "kisaan:bookings:alertImminent":
                $application->add(new AlertImminentBookingsCommand());
                $arguments["--delay"] = 1440;//kisaan.booking.imminent_delay
                $arguments["--test"] = true;
                break;
            case "kisaan:bookings:validate":
                $application->add(new ValidateBookingsCommand());
                $arguments["--delay"] = -1440;//kisaan.booking.validated_delay
                $arguments["--moment"] = 'start';//kisaan.booking.validated_moment
                $arguments["--test"] = true;
                break;
            case "kisaan:bookings:checkBankWires":
                $application->add(new CheckBookingsBankWiresCommand());
                break;
            case "kisaan:listings:alertUpdateCalendars":
                $application->add(new AlertListingsCalendarUpdateCommand());
                break;
            case "kisaan_listing_search:computeNotation":
                $application->add(new ComputeListingsNotationCommand());
                break;
            default:
                echo "Command not found";
        }

        $command = $application->find($name);
        $arguments["command"] = $command->getName();

        $this->tester = new CommandTester($command);
        $this->tester->execute($arguments);
    }


    /**
     * @Then /^(?:|I|He) should see "([^"]*)" on console$/
     *
     * @param string $string
     */
    public function iShouldSee($string)
    {
        PHPUnit_Framework_TestCase::AssertEquals(
            trim($string),
            trim($this->tester->getDisplay()),
            sprintf("Command return unexpected result \"%s\"", $this->tester->getDisplay())
        );
    }

    /**
     * @Then /^I should see "([^"]*)" on console messages$/
     *
     * @param string $string
     */
    public function iShouldSeeInMessages($string)
    {
        PHPUnit_Framework_TestCase::assertRegExp(
            "/$string/",
            trim($this->tester->getDisplay()),
            sprintf("Command return unexpected result \"%s\"", $this->tester->getDisplay())
        );
    }

}