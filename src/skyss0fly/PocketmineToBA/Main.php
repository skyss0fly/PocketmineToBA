<?php

namespace skyss0fly\PocketmineToBA;

use pocketmine\plugin\PluginBase;

class Main extends PluginBase {

    public function onEnable(): void {
        $this->getLogger()->info("PocketmineToBA enabled!");

        $converter = new Converter($this);
        $pluginFolder = $this->getServer()->getPluginManager()->getPlugin("PocketmineToBA")->getDataFolder() . "../../";
      
        $this->getLogger()->info("Scanning plugins folder for plugins to convert...");

        foreach (scandir($pluginFolder) as $pluginName) {
            $pluginPath = $pluginFolder . $pluginName;

            // Only convert folders (not files) that are not this plugin and have a plugin.yml
            if (
                is_dir($pluginPath) &&
                $pluginName !== $this->getName() &&
                file_exists($pluginPath . "/plugin.yml")
            ) {
                $this->getLogger()->info("Converting plugin: $pluginName");
                $converter->convertPlugin($pluginPath);
            }
        }

        $this->getLogger()->info("All plugins processed.");
    }
}
