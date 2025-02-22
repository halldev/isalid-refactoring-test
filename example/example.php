<?php

require_once __DIR__ . '/../vendor/autoload.php';

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
require_once __DIR__ . '/../src/Service/MessageBuilder.php';
require_once __DIR__ . '/../src/Service/MessageReplaceWorker/ReplaceWorkerInterface.php';
require_once __DIR__ . '/../src/Service/MessageReplaceWorker/DestinationLinkReplaceWorker.php';
require_once __DIR__ . '/../src/Service/MessageReplaceWorker/DestinationNameReplaceWorker.php';
require_once __DIR__ . '/../src/Service/MessageReplaceWorker/QuoteSummaryReplaceWorker.php';
require_once __DIR__ . '/../src/Service/MessageReplaceWorker/QuoteSummaryHtmlReplaceWorker.php';
require_once __DIR__ . '/../src/Service/MessageReplaceWorker/UserNameReplaceWorker.php';
require_once __DIR__ . '/../src/Service/QuoteRender/RenderInterface.php';
require_once __DIR__ . '/../src/Service/QuoteRender/RenderHtml.php';
require_once __DIR__ . '/../src/Service/QuoteRender/RenderText.php';
require_once __DIR__ . '/../src/Exception/InvalidReplaceWorkerException.php';
require_once __DIR__ . '/../src/TemplateManager.php';

$faker = \Faker\Factory::create();

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
        'quote' => new Quote($faker->randomNumber(), $faker->randomNumber(), $faker->randomNumber(), $faker->date())
    ]
);

echo $message->getSubject() . "\n" . $message->getContent();
