# recipe-scraper
A recipe scraping library which makes it easy to scrape recipes from popular sites around the web.

Current site support is still limited. A full list is available in [SITE-SUPPORT.md](SITE-SUPPORT.md).

## Requirements
Composer, PHP 7.0 or later.

## Installation
```
composer require ssnepenthe/recipe-scraper
```

## Usage
Scraper instances work on Symfony DomCrawler instances. These can be created however you choose, but the easiest is to use a BrowserKit implementation like Goutte:

```PHP
$client = new Goutte\Client;
$crawler = $client->request('GET', 'http://allrecipes.com/recipe/139917/joses-shrimp-ceviche/');
```

If you only need to scrape recipes from a single site, you can use the corresponding class from `src/Scrapers`:

```PHP
$scraper = new RecipeScraper\Scrapers\AllRecipesCom;
```

If you want to be able to scrape recipes from all supported sites, create a `DelegatingScraper` using the `Factory` class:

```PHP
$scraper = RecipeScraper\Factory::make();
```

Check whether a scraper supports a given crawler using the `->supports()` method:

```PHP
$scraper->supports($crawler); // true
```

Finally, scrape a recipe by passing the crawler to the `->scrape()` method:

```PHP
$recipe = $scraper->scrape($crawler);
```

The following properties are guaranteed to be set on the `$recipe` array:

```PHP
$recipe['author'] // string|null
$recipe['categories'] // string[]|null
$recipe['cookingMethod'] // string|null
$recipe['cookTime'] // string|null
$recipe['cuisines'] // string[]|null
$recipe['description'] // string|null
$recipe['image'] // string|null
$recipe['ingredients'] // string[]|null
$recipe['instructions'] // string[]|null
$recipe['name'] // string|null
$recipe['notes'] // string[]|null
$recipe['prepTime'] // string|null
$recipe['publisher'] // string|null
$recipe['totalTime'] // string|null
$recipe['url'] // string|null
$recipe['yield'] // string|null
```

If the `->scrape()` method is called on an unsupported crawler instance, all values in `$recipe` will be null.

Altogether:

```PHP
$scraper = RecipeScraper\Factory::make();
$client = new Goutte\Client;
$url = 'http://allrecipes.com/recipe/139917/joses-shrimp-ceviche/';
$crawler = $client->request('GET', $url);

if ($scraper->supports($crawler)) {
    var_dump($scraper->scrape($crawler));
} else {
    var_dump("{$url} not currently supported!");
}
```

OUTPUT:

```PHP
array(15) {
    'author' => string(9) "carrielee"
    'categories' => array(2) {
        [0] => string(21) "Appetizers and Snacks"
        [1] => string(5) "Spicy"
    }
    'cookingMethod' => NULL
    'cookTime' => string(5) "PT10M"
    'cuisines' => NULL
    'description' => string(336) ""I've looked all over the net and haven't found a shrimp ceviche quite like this one! My friends absolutely love it and beg me for the recipe! You can always double it for larger parties--it goes FAST! Serve as a dip with tortilla chips or as a topping on a tostada spread with mayo. The fearless palate might like this with hot sauce.""
    'image' => string(66) "https://images.media-allrecipes.com/userphotos/560x315/1364063.jpg"
    'ingredients' => array(9) {
        [0] => string(41) "1 pound peeled and deveined medium shrimp"
        [1] => string(22) "1 cup fresh lime juice"
        [2] => string(23) "10 plum tomatoes, diced"
        [3] => string(27) "1 large yellow onion, diced"
        [4] => string(49) "1 jalapeno pepper, seeded and minced, or to taste"
        [5] => string(28) "2 avocados, diced (optional)"
        [6] => string(31) "2 ribs celery, diced (optional)"
        [7] => string(31) "chopped fresh cilantro to taste"
        [8] => string(24) "salt and pepper to taste"
    }
    'instructions' => array(2) {
        [0] => string(294) "Place shrimp in a glass bowl and cover with lime juice to marinate (or 'cook') for about 10 minutes, or until they turn pink and opaque. Meanwhile, place the plum tomatoes, onion and jalapeno (and avocados and celery, if using) in a large, non-reactive (stainless steel, glass or plastic) bowl."
        [1] => string(200) "Remove shrimp from lime juice, reserving juice. Dice shrimp and add to the bowl of vegetables. Pour in the remaining lime juice marinade. Add cilantro and salt and pepper to taste. Toss gently to mix."
    }
    'name' => string(21) "Jose's Shrimp Ceviche"
    'notes' => NULL
    'prepTime' => string(5) "PT45M"
    'publisher' => NULL
    'totalTime' => string(5) "PT55M"
    'url' => string(62) "https://www.allrecipes.com/recipe/139917/joses-shrimp-ceviche/"
    'yield' => string(2) "20"
}
```

## Limitations
Scraping seems to be (unfortunately) the best option available for extracting structured recipes from the sites supported by this scraper.

Just keep in mind that any template updates to the target site have a very high chance of breaking this scraper.

If you notice support for a particular site is broken, please submit an issue or pull request.

For this same reason, it **may** be preferable to track the master branch of this repo, although I will try to tag a new release after any site-specific updates.
