<?php

require_once __DIR__.'/../src/autoload.php';

use Symfony\Framework\Kernel;
use Symfony\Components\DependencyInjection\Loader\LoaderInterface;
use Symfony\Components\DependencyInjection\ContainerBuilder;

class MiamKernel extends Kernel
{
    public function registerRootDir()
    {
        return __DIR__;
    }

    public function boot()
    {
        Symfony\Components\OutputEscaper\Escaper::markClassesAsSafe(array(
            'Symfony\Components\Form\Form',
            'Symfony\Components\Form\Field'
        ));

        #TODO remove me 
        foreach(array('BundleDoctrineUserBundleEntityUserProxy', 'ApplicationMiamBundleEntityProjectProxy', 'ApplicationMiamBundleEntityStoryProxy', 'ApplicationMiamBundleEntitySprintProxy', 'ApplicationMiamBundleEntityTimelineEntryProxy') as $class) {
            include_once(__DIR__.'/cache/'.$this->getEnvironment().'/doctrine/orm/Proxies/'.$class.'.php');
        }
        
        return parent::boot();
    }

    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Framework\KernelBundle(),
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),

            // enable third-party bundles
            new Symfony\Bundle\ZendBundle\ZendBundle(),
            new Symfony\Bundle\DoctrineBundle\DoctrineBundle(),
            new Symfony\Bundle\DoctrineMigrationsBundle\DoctrineMigrationsBundle(),

            // register your bundles here
            new Bundle\MarkdownBundle\MarkdownBundle(),
            new Bundle\DoctrineUserBundle\DoctrineUserBundle(),

            // register your applications here
            new Application\MiamBundle\MiamBundle()
        );

        return $bundles;
    }

    public function registerBundleDirs()
    {
        return array(
            'Application'        => __DIR__.'/../src/Application',
            'Bundle'             => __DIR__.'/../src/Bundle',
            'Symfony\\Bundle'    => __DIR__.'/../src/vendor/Symfony/src/Symfony/Bundle',
        );
    }

    /**
     * Returns the config_{environment}_local.yml file or 
     * the default config_{environment}.yml if it does not exist.
     * Useful to override development password.
     *
     * @param string Environment
     * @return The configuration file path
     */
    protected function getLocalConfigurationFile($environment)
    {
        $basePath = __DIR__.'/config/config_';
        $file = $basePath.$environment.'_local.yml';

        if(\file_exists($file))
        {
            return $file;
        }

        return $basePath.$environment.'.yml';
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $container = new ContainerBuilder();

        $loader->load($this->getLocalConfigurationFile($this->getEnvironment()));

        $container->setParameter('validator.message_interpolator.class', 'Application\\MiamBundle\\Validator\\NoValidationXliffMessageInterpolator');

        return $container;
    }
}
