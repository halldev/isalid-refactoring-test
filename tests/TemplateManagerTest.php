<?php

require_once __DIR__ . '/../src/Entity/Destination.php';
require_once __DIR__ . '/../src/Entity/Quote.php';
require_once __DIR__ . '/../src/Entity/Site.php';
require_once __DIR__ . '/../src/Entity/Template.php';
require_once __DIR__ . '/../src/Entity/User.php';
require_once __DIR__ . '/../src/Helper/SingletonTrait.php';
require_once __DIR__ . '/../src/Context/ApplicationContext.php';
require_once __DIR__ . '/../src/Repository/Repository.php';
require_once __DIR__ . '/../src/Repository/DestinationRepository.php';
require_once __DIR__ . '/../src/Repository/QuoteRepository.php';
require_once __DIR__ . '/../src/Repository/SiteRepository.php';
require_once __DIR__ . '/../src/TemplateManager.php';
require_once __DIR__ . '/../src/Service/MessageBuilder.php';
require_once __DIR__ . '/../src/Service/MessageReplaceWorker/ReplaceWorkerInterface.php';
require_once __DIR__ . '/../src/Service/MessageReplaceWorker/DestinationLinkReplaceWorker.php';
require_once __DIR__ . '/../src/Service/MessageReplaceWorker/DestinationNameReplaceWorker.php';
require_once __DIR__ . '/../src/Service/MessageReplaceWorker/QuoteSummaryReplaceWorker.php';
require_once __DIR__ . '/../src/Service/MessageReplaceWorker/QuoteSummaryHtmlReplaceWorker.php';
require_once __DIR__ . '/../src/Service/MessageReplaceWorker/UserNameReplaceWorker.php';

class TemplateManagerTest extends PHPUnit_Framework_TestCase
{
    /**
     * Init the mocks
     */
    public function setUp()
    {
    }

    /**
     * Closes the mocks
     */
    public function tearDown()
    {
    }

    /**
     * @test
     */
    public function test()
    {
        $faker = \Faker\Factory::create();

        $destinationId = $faker->randomNumber();
        $expectedDestination = DestinationRepository::getInstance()->getById($destinationId);
        $expectedUser = ApplicationContext::getInstance()->getCurrentUser();

        $quote = new Quote($faker->randomNumber(), $faker->randomNumber(), $destinationId, $faker->date());

        $template = new Template(
            1,
            'Votre livraison à [quote:destination_name]',
            "
Bonjour [user:first_name],

Merci de nous avoir contacté pour votre livraison à [quote:destination_name].

Bien cordialement,

L'équipe de Shipper
");
        $templateManager = new TemplateManager();

        $message = $templateManager->getTemplateComputed(
            $template,
            [
                'quote' => $quote
            ]
        );

        $this->assertEquals('Votre livraison à ' . $expectedDestination->getCountryName(), $message->getSubject());
        $this->assertEquals("
Bonjour " . $expectedUser->getFirstname() . ",

Merci de nous avoir contacté pour votre livraison à " . $expectedDestination->getCountryName() . ".

Bien cordialement,

L'équipe de Shipper
", $message->getContent());
    }
}
