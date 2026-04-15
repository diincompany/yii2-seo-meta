<?php

namespace diincompany\seometa\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Url;
use yii\web\View;

class SeoMeta extends Widget
{
    public array $config = [];
    public string $paramsKey = 'seo';

    public function run(): string
    {
        $view = $this->getView();
        $config = array_merge($this->getDefaultConfig(), Yii::$app->params[$this->paramsKey] ?? [], $this->config);

        $siteName = $config['siteName'];
        $description = trim((string) ($view->params['meta_description'] ?? $config['defaultDescription']));
        $keywords = trim((string) ($view->params['meta_keywords'] ?? $config['defaultKeywords']));
        $title = trim((string) ($view->params['og_title'] ?? ($view->title ?: $siteName)));
        $ogType = trim((string) ($view->params['og_type'] ?? $config['defaultType']));
        $twitterCard = trim((string) ($view->params['twitter_card'] ?? $config['twitterCard']));
        $image = $view->params['og_image'] ?? $config['defaultImage'];
        $canonicalUrl = $view->params['canonical_url'] ?? Yii::$app->request->absoluteUrl;

        $this->registerMetaTags($view, [
            'siteName' => $siteName,
            'description' => $description,
            'keywords' => $keywords,
            'title' => $title,
            'ogType' => $ogType,
            'twitterCard' => $twitterCard,
            'canonicalUrl' => $this->normalizeUrl($canonicalUrl),
            'imageUrl' => $this->normalizeUrl($image),
            'imageType' => (string) $config['imageType'],
            'imageWidth' => (string) $config['imageWidth'],
            'imageHeight' => (string) $config['imageHeight'],
            'icon' => $this->normalizeIcon($config['icon']),
        ]);

        return '';
    }

    protected function getDefaultConfig(): array
    {
        return [
            'siteName' => Yii::$app->name,
            'defaultDescription' => '',
            'defaultKeywords' => '',
            'defaultImage' => '/images/opengraph-default.svg',
            'twitterCard' => 'summary_large_image',
            'defaultType' => 'website',
            'imageType' => 'image/svg+xml',
            'imageWidth' => 1200,
            'imageHeight' => 630,
            'icon' => '@web/favicon.ico',
        ];
    }

    protected function registerMetaTags(View $view, array $meta): void
    {
        $view->registerCsrfMetaTags();
        $view->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
        $view->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
        $view->registerMetaTag(['name' => 'description', 'content' => $meta['description']], 'description');
        $view->registerMetaTag(['name' => 'keywords', 'content' => $meta['keywords']], 'keywords');
        $view->registerMetaTag(['property' => 'og:locale', 'content' => str_replace('-', '_', Yii::$app->language)], 'og:locale');
        $view->registerMetaTag(['property' => 'og:site_name', 'content' => $meta['siteName']], 'og:site_name');
        $view->registerMetaTag(['property' => 'og:title', 'content' => $meta['title']], 'og:title');
        $view->registerMetaTag(['property' => 'og:description', 'content' => $meta['description']], 'og:description');
        $view->registerMetaTag(['property' => 'og:type', 'content' => $meta['ogType']], 'og:type');
        $view->registerMetaTag(['property' => 'og:url', 'content' => $meta['canonicalUrl']], 'og:url');
        $view->registerMetaTag(['property' => 'og:image', 'content' => $meta['imageUrl']], 'og:image');
        $view->registerMetaTag(['property' => 'og:image:type', 'content' => $meta['imageType']], 'og:image:type');
        $view->registerMetaTag(['property' => 'og:image:width', 'content' => $meta['imageWidth']], 'og:image:width');
        $view->registerMetaTag(['property' => 'og:image:height', 'content' => $meta['imageHeight']], 'og:image:height');
        $view->registerMetaTag(['property' => 'og:image:alt', 'content' => $meta['title']], 'og:image:alt');
        $view->registerMetaTag(['name' => 'twitter:card', 'content' => $meta['twitterCard']], 'twitter:card');
        $view->registerMetaTag(['name' => 'twitter:title', 'content' => $meta['title']], 'twitter:title');
        $view->registerMetaTag(['name' => 'twitter:description', 'content' => $meta['description']], 'twitter:description');
        $view->registerMetaTag(['name' => 'twitter:image', 'content' => $meta['imageUrl']], 'twitter:image');
        $view->registerLinkTag(['rel' => 'canonical', 'href' => $meta['canonicalUrl']], 'canonical');
        $view->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => $meta['icon']], 'icon');
    }

    protected function normalizeIcon(string $icon): string
    {
        return strpos($icon, '@') === 0 ? Yii::getAlias($icon) : $icon;
    }

    protected function normalizeUrl($url): string
    {
        if (is_array($url)) {
            return Url::to($url, true);
        }

        return preg_match('/^https?:\/\//i', (string) $url) ? (string) $url : Url::to((string) $url, true);
    }
}
