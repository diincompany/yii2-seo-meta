# diincompany/yii2-seo-meta

Widget reutilizable para registrar meta tags SEO, Open Graph y Twitter Cards en aplicaciones Yii2.

## Instalacion

```bash
composer require diincompany/yii2-seo-meta
```

Si se usa como package local por path repository:

```json
{
  "repositories": [
    {
      "type": "path",
      "url": "src/components/yii2-seo-meta",
      "options": {
        "symlink": true
      }
    }
  ]
}
```

## Uso

En el layout:

```php
<?php

use diincompany\seometa\widgets\SeoMeta;

echo SeoMeta::widget();
```

## Configuracion

En `config/params.php`:

```php
'seo' => [
    'siteName' => 'Mi Tienda',
    'defaultDescription' => 'Catalogo, novedades y productos destacados.',
    'defaultKeywords' => 'ecommerce, tienda en linea, catalogo',
    'defaultImage' => '/images/opengraph-default.png',
    'twitterCard' => 'summary_large_image',
    'defaultType' => 'website',
    'imageType' => 'image/png',
    'imageWidth' => 1200,
    'imageHeight' => 630,
    'icon' => '@web/favicon.ico',
],
```

Overrides por vista:

```php
$this->params['meta_description'] = 'Descripcion especifica';
$this->params['meta_keywords'] = 'producto, categoria';
$this->params['og_title'] = 'Titulo para compartir';
$this->params['og_type'] = 'product';
$this->params['og_image'] = '/images/producto.png';
$this->params['canonical_url'] = ['/products/view', 'slug' => 'mi-producto'];
```
