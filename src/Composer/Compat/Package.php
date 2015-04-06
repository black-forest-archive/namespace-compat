<?php

/**
 * Composer Namespace Combat
 *
 * PHP version 5
 *
 * @copyright  BlackForest <https://github.com/black-forest>
 * @author     Dominik Tomasi <dominik.tomasi@gmail.com>
 * @author     Sven Baumann <baumannsv@gmail.com>
 * @package    blackforest/namespace-compat
 * @license    LGPL
 */

namespace BlackForest\Composer\Compat;
use Composer\Composer;


/**
 * Class Repository
 * @package blackforest/namespace-compat
 */
class Package
{
    protected $composer;

    protected $package = array();

    /**
     * initialize the object and add the composer to $this
     *
     * @param $composer
     */
    public function __construct($composer)
    {
        $this->composer = $composer;
    }

    /**
     * magic method to get protected variable
     *
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->$name;
    }

    /**
     * push all namespace-compat repositories to $this->repositories
     */
    public function compile()
    {
        /** @var \Composer\Autoload\AutoloadGenerator $autoloadGenerator */
        $autoloadGenerator = $this->composer->getAutoloadGenerator();

        $mainPackage = $this->composer->getPackage();
        $packages = $this->getCompatPackages();

        if (count($packages) > 0) {
            $mainPackageMap = $autoloadGenerator->buildPackageMap($this->composer->getInstallationManager(), $mainPackage, array());
            $mainAutoload = $autoloadGenerator->parseAutoloads($mainPackageMap, $mainPackage);

            foreach ($packages as $package) {
                $packageMap = $autoloadGenerator->buildPackageMap($this->composer->getInstallationManager(), $mainPackage, array($package));
                $autoload = $autoloadGenerator->parseAutoloads($packageMap, $package);

                // check if main package in $autoload and restore path
                if ($package instanceof \Composer\Package\RootPackage) {
                    $autoload = $mainAutoload;
                }

                array_push($this->package, array('package' => $package, 'autoload' => $autoload));
            }
        }
    }

    /**
     * return all compat packages
     *
     * @return array
     */
    protected function getCompatPackages()
    {
        $allPackages = array_merge(array($this->composer->getPackage()), $this->composer->getRepositoryManager()->getLocalRepository()->getPackages());

        $packages = array();

        foreach ($allPackages as $package) {
            if ($package->getName() === "blackforest/namespace-compat") {
                continue;
            }

            $extra = $package->getExtra();

            if (array_key_exists('namespace-compat', $extra)) {
                array_push($packages, $package);
            }
        }

        return $packages;
    }
}
