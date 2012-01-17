# GuzzleBundle
## Introduction
GuzzleBundle is a Symfony2 bundle for integrating the [Guzzle PHP library](http://github.com/guzzle/guzzle) in your project.

It’s not quite finished.

## Installation
Install Guzzle:

    git submodule add git://github.com/guzzle/guzzle.git vendor/guzzle

Install GuzzleBundle:

    git submodule add git://github.com/ddeboer/GuzzleBundle vendor/bundles/Ddeboer/GuzzleBundle

## Autoloader
Add Guzzle and Ddeboer namespace to your autoloader:

    // app/autoload.php
    $loader->registerNamespaces(array(
        // ...
        'Guzzle'           => __DIR__.'/../vendor/guzzle/src',
        'Ddeboer'          => __DIR__.'/../vendor/bundles',
        // ...
    ));

## Application kernel
Add GuzzleBundle to your application kernel:

    // app/AppKernel.php
    public function registerBundles()
    {
        // ...
        new Ddeboer\GuzzleBundle\DdeboerGuzzleBundle(),
        // ...
    }

## Configure the Guzzle service builder

    // app/config/config.yml
    ddeboer_guzzle:
    service_builder:
      configuration_file: "%kernel.root_dir%/config/webservices.xml"

And add a Guzzle services configuration file. See the [Guzzle documentation](http://guzzlephp.org/tour/using_services.html#instantiating-web-service-clients-using-a-servicebuilder).

    // app/config/webservices.xml
    <?xml version="1.0" ?>
    <guzzle>
        <clients>
            <!-- Abstract service to store AWS account credentials -->
            <client name="abstract.aws">
                <param name="access_key" value="12345" />
                <param name="secret_key" value="abcd" />
            </client>
            <!-- Amazon S3 client that extends the abstract client -->
            <client name="s3" classs="Guzzle.Aws.S3.S3Client" extends="abstract.aws">
                <param name="devpay_product_token" value="XYZ" />
                <param name="devpay_user_token" value="123" />
            </client>
            <client name="simple_db" class="Guzzle.Aws.SimpleDb.SimpleDbClient" extends="abstract.aws" />
            <client name="sqs" class="Guzzle.Aws.Sqs.SqsClient" extends="abstract.aws" />
            <!-- Unfuddle client -->
            <client name="unfuddle" class="Guzzle.Unfuddle.UnfuddleClient">
                <param name="username" value="test-user" />
                <param name="password" value="my-password" />
                <param name="subdomain" value="my-subdomain" />
            </client>
        </clients>
    </guzzle>
