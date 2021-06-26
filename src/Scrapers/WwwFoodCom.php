<?php

namespace RecipeScraper\Scrapers;

use RecipeScraper\Arr;
use RecipeScraper\ExtractsDataFromCrawler;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Most recipes are on the new design which provides JSON-LD.
 * Some are still served with the old design which does not.
 */
class WwwFoodCom extends SchemaOrgJsonLd
{
    protected function extractInstructions(Crawler $crawler, array $json)
    {
        $instructions = Arr::get($json, 'recipeInstructions');

        if (! is_array($instructions)) {
            return $this->extractArray($crawler, '[itemprop="recipeInstructions"] li');
        }

        $instructions = array_filter($instructions, function ($instruction) {
            return Arr::ofStrings($instruction) && array_key_exists('text', $instruction);
        });

        return array_column($instructions, 'text');
    }
}
