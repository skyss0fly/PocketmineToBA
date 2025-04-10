<?php

namespace skyss0fly\PocketmineToBA;

use pocketmine\plugin\PluginBase;

class Converter {
    private PluginBase $plugin;

    public function __construct(PluginBase $plugin) {
        $this->plugin = $plugin;
    }

    public function convertPlugin(string $pluginDir): void {
        $files = $this->getPHPFiles($pluginDir);

        foreach ($files as $file) {
            $contents = file_get_contents($file);

            // TODO: Keep adding things to convert
            $replacements = [
                'pocketmine\player\Player' => 'pocketmine\Player',
                ': void' => '', // remove return type
                'use pocketmine\event\player\PlayerChatEvent as PlayerChatEvent;' => 'use pocketmine\event\player\PlayerChatEvent;',
            ];

            $modified = str_replace(array_keys($replacements), array_values($replacements), $contents);

            file_put_contents($file, $modified);
        }

        $this->plugin->getLogger()->info("Conversion complete.");
    }

    private function getPHPFiles(string $dir): array {
        $rii = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir));
        $files = [];

        foreach ($rii as $file) {
            if (!$file->isDir() && $file->getExtension() === 'php') {
                $files[] = $file->getPathname();
            }
        }

        return $files;
    }
}
