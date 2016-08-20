<?php

declare(strict_types = 1);

namespace VinaiKopp\TechnicalDebtAggregate\Test\Integration;

use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Component\ComponentRegistrar;
use Magento\Framework\Module\ModuleListInterface;
use Magento\TestFramework\ObjectManager;

class TechnicalDebtModuleConfigTest extends \PHPUnit_Framework_TestCase
{
    private $moduleName = 'VinaiKopp_TechnicalDebtAggregate';

    public function testModuleIsRegistered()
    {
        $registrar = new ComponentRegistrar();
        $paths = $registrar->getPaths(ComponentRegistrar::MODULE);
        $this->assertArrayHasKey($this->moduleName, $paths);
    }

    public function testModuleIsActive()
    {
        $objectManager = ObjectManager::getInstance();
        $dirList = $objectManager->create(DirectoryList::class, ['root' => BP]);
        $configReader = $objectManager->create(DeploymentConfig\Reader::class, ['dirList' => $dirList]);
        $moduleConfig = $objectManager->create(DeploymentConfig::class, ['reader' => $configReader]);
        
        /** @var ModuleListInterface $moduleList */
        $moduleList = $objectManager->create(ModuleListInterface::class, ['config' => $moduleConfig]);
        $this->assertTrue($moduleList->has($this->moduleName));
    }
}
