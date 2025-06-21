<?php

namespace Vhar\EmbedVideo;


class VideoHostingService
{
    /**
     * The array of class video hosting aliases and their class names.
     * @var array
     */
    protected $classHostingAliases = [];

    /**
     * Register a class-based component alias directive.
     * @param string $class
     * @param string $alias
     * @return void
     */
    public function hosting(string $alias, string $class)
    {
        if (str_contains($alias, '\\')) {
            [$class, $alias] = [$alias, $class];
        }

        $this->classHostingAliases[$alias] = $class;
    }

    /**
     * Get the registered video hosting handler class aliases.
     * @return array
     */
    public function getClassHostingAliases()
    {
        return $this->classHostingAliases;
    }

    /**
     * Get the video hosting handler class by alias.
     * @param string $alias
     * @return string|null
     */
    public function getClassHostingAlias(string $alias)
    {
        return $this->classHostingAliases[$alias] ?? null;
    }
}
