<?php

namespace Vhar\EmbedVideo;

use Illuminate\Container\Container;
use Vhar\EmbedVideo\Contracts\EmbedVideoInterface;


class EmbedVideoService
{
    /**
     * The current video hosting class-handler service.
     * @var EmbedVideoInterface
     */
    protected $hosting;

    /**
     * Set video hosting handler class
     * @param string $alias
     * @return EmbedVideoInterface
     * @throws \InvalidArgumentException
     */
    public function hosting(string $alias): EmbedVideoInterface
    {
        $handler = Container::getInstance()->make('videohosting')->getClassHostingAlias($alias);

        if (is_null($handler)) {
            throw new \InvalidArgumentException(__("Class for :alias is not defined", ['alias' => $alias]));
        }

        return $this->hosting = new $handler;
    }

    /**
     * @param string $method
     * @param array $args
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function __call(string $method, array $args): mixed
    {
        $domain = parse_url($args[0], PHP_URL_HOST);

        if (is_null($domain)) {
            throw new \InvalidArgumentException(__('The argument must be a URL.'));
        }

        $alias = $this->getAlias($domain);

        if (is_null($alias)) {
            throw new \InvalidArgumentException(__("There are no registered handlers for the domain :domain", ['domain' => $domain]));
        }

        return $this->hosting($alias)->{$method}(...$args);
    }

    /**
     * Get the registered video hosting handler class by hosting domain from URL
     * @param string $domain
     * @return string|null
     */
    private function getAlias(string $domain): ?string
    {
        $handlers = Container::getInstance()->make('videohosting')->getClassHostingAliases();

        $domain = str_replace('www.', '', $domain);

        foreach ($handlers as $alias => $handler) {
            $hosting = new $handler;

            if (in_array($domain, $hosting->allowedDomains())) {
                return $alias;
            }
        }

        return null;
    }
}
