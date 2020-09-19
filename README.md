# SilverStripe News Module

## Requirements

* SilverStripe CMS 4.0+
* SilverStripe Assets 1.0+
* SilverStripe Asset Admin Module 1.0+

## Installation

```
composer require wwnorden/news
```

## Documentation

Create a news article and fill out the form fields. 
At the End set status to active. 
Create a new page type news page and publish it. 
You see the new created news article on it.
 
## Hints
For overwriting the csv Bulkloader with your custom bulkloader file
just insert something like the following configuration to your yaml file:

```yaml
WWN\News\NewsAdmin:
  model_importers:
    WWN\News\NewsArticle: '\NewsCsvBulkLoader'
```
