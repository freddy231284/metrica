<?php
/**
 * JmsSerializer Service Provider
 */

namespace App\Provider;

use Doctrine\Common\Annotations\AnnotationRegistry;
use JMS\Serializer\Naming\CamelCaseNamingStrategy;
use JMS\Serializer\Naming\IdenticalPropertyNamingStrategy;
use JMS\Serializer\Naming\PropertyNamingStrategyInterface;
use JMS\Serializer\Naming\SerializedNameAnnotationStrategy;
use JMS\Serializer\SerializerBuilder;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Api\BootableProviderInterface;
use Silex\Application;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

/**
 * JMS Serializer integration for Silex.
 */
class JmsSerializerServiceProvider implements ServiceProviderInterface, BootableProviderInterface
{
    /**
     * Register the jms/serializer annotations
     *
     * @param Application $app
     */
    public function boot(Application $app)
    {
        if ($app->offsetExists("jms_serializer.srcDir")) {
            AnnotationRegistry::registerAutoloadNamespace("JMS\\Serializer\\Annotation", $app["jms_serializer.srcDir"]);
        }
    }

    /**
     * Register the serializer and serializer.builder services
     *
     * @param Container $app
     *
     * @throws ServiceUnavailableHttpException
     */
    public function register(Container $app)
    {
        $app["jms_serializer.namingStrategy"] = "CamelCase";
        $app["jms_serializer.namingStrategy.separator"] = "_";
        $app["jms_serializer.namingStrategy.lowerCase"] = true;

        $app["jms_serializer.propertyNamingStrategy"] = $this->PropertyNamingStrategyService($app);
        $app["jms_serializer.builder"] = $this->BuilderService($app);
        $app["jms_serializer"] = function (Container $app) {
            return $app["jms_serializer.builder"]->build();
        };
    }

    /**
     * Set the serialization naming strategy
     *
     * @param Container $app
     * @return PropertyNamingStrategyInterface
     *
     * @throws ServiceUnavailableHttpException
     */
    protected function PropertyNamingStrategyService(Container $app)
    {
        if ($app["jms_serializer.namingStrategy"] instanceof PropertyNamingStrategyInterface) {
            $namingStrategy = $app["jms_serializer.namingStrategy"];
        } else {
            switch ($app["jms_serializer.namingStrategy"]) {
                case "IdenticalProperty":
                    $namingStrategy = new IdenticalPropertyNamingStrategy();
                    break;
                case "CamelCase":
                    $separator = $app["jms_serializer.namingStrategy.separator"];
                    $lowerCase = $app["jms_serializer.namingStrategy.lowerCase"];
                    $namingStrategy = new CamelCaseNamingStrategy($separator, $lowerCase);
                    break;
                default:
                    throw new ServiceUnavailableHttpException(
                        null,
                        "Unknown property naming strategy '{$app["jms_serializer.namingStrategy"]}'.  " .
                        "Allowed values are 'IdenticalProperty' or 'CamelCase'"
                    );
            }

            $namingStrategy = new SerializedNameAnnotationStrategy($namingStrategy);
        }

        return $namingStrategy;
    }

    /**
     * Set the serialization builder
     *
     * @param Container $app
     * @return static
     */
    protected function BuilderService(Container $app) {
        $serializerBuilder = SerializerBuilder::create();

        if ($app->offsetExists("debug")) {
            $serializerBuilder->setDebug($app["debug"]);
        }

        if ($app->offsetExists("jms_serializer.annotationReader")) {
            $serializerBuilder->setAnnotationReader($app["jms_serializer.annotationReader"]);
        }

        if ($app->offsetExists("jms_serializer.cacheDir")) {
            $serializerBuilder->setCacheDir($app["jms_serializer.cacheDir"]);
        }

        if ($app->offsetExists("jms_serializer.configureHandlers")) {
            $serializerBuilder->configureHandlers($app["jms_serializer.configureHandlers"]);
        }

        if ($app->offsetExists("jms_serializer.configureListeners")) {
            $serializerBuilder->configureListeners($app["jms_serializer.configureListeners"]);
        }

        if ($app->offsetExists("jms_serializer.objectConstructor")) {
            $serializerBuilder->setObjectConstructor($app["jms_serializer.objectConstructor"]);
        }

        $serializerBuilder->setPropertyNamingStrategy($app["jms_serializer.propertyNamingStrategy"]);

        if ($app->offsetExists("jms_serializer.serializationVisitors")) {
            $this->setSerializationVisitors($app, $serializerBuilder);
        }

        if ($app->offsetExists("jms_serializer.deserializationVisitors")) {
            $this->setDeserializationVisitors($app, $serializerBuilder);
        }

        if ($app->offsetExists("jms_serializer.includeInterfaceMetadata")) {
            $serializerBuilder->includeInterfaceMetadata($app["jms_serializer.includeInterfaceMetadata"]);
        }

        if ($app->offsetExists("jms_serializer.metadataDirs")) {
            $serializerBuilder->setMetadataDirs($app["jms_serializer.metadataDirs"]);
        }

        return $serializerBuilder;
    }

    /**
     * Override default serialization vistors
     *
     * @param Container $app
     * @param SerializerBuilder $serializerBuilder
     */
    protected function setSerializationVisitors(Container $app, SerializerBuilder $serializerBuilder)
    {
        $serializerBuilder->addDefaultSerializationVisitors();

        foreach ($app["jms_serializer.serializationVisitors"] as $format => $visitor) {
            $serializerBuilder->setSerializationVisitor($format, $visitor);
        }
    }

    /**
     * Override default deserialization visitors
     *
     * @param Container $app
     * @param SerializerBuilder $serializerBuilder
     */
    protected function setDeserializationVisitors(Container $app, SerializerBuilder $serializerBuilder)
    {
        $serializerBuilder->addDefaultDeserializationVisitors();

        foreach ($app["jms_serializer.deserializationVisitors"] as $format => $visitor) {
            $serializerBuilder->setDeserializationVisitor($format, $visitor);
        }
    }
}