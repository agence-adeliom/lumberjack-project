# Documentation d'utilisation des macros d'image

Cette documentation explique comment utiliser les macros d'image dans votre projet en utilisant la macro principale `render_picture` ainsi que les macros associées. Ces macros vous permettent de générer des balises `<picture>` avec des balises `<source>` pour les différentes sources d'images, et d'insérer des balises `<img>` avec des attributs d'image appropriés.

## `render_picture`

La macro `render_picture` génère une balise `<picture>` avec des balises `<source>` pour les différentes sources d'images en fonction des paramètres spécifiés, puis insère la balise `<img>` avec les attributs d'image appropriés.

### Paramètres :

- `image_src` : Chemin de l'image source.
- `obj` (facultatif) : Un objet contenant les propriétés d'image et de balise. Les propriétés non spécifiées seront utilisées avec des valeurs par défaut.

### Exemples d'utilisation :

#### `Exemple générique`
```twig
{% import "components/content/media/image.html.twig" as image %}

{% set image_properties = {
    'alt': 'Une belle image',
    'width': 800,
    'height': 600,
    'webp': true,
    'loading': 'lazy',
    'use_lazysizes': true,
    'loading_effect': 'blur',
    'low_quality': '/chemin/vers/low_quality_image.jpg'
    'responsive_sizes': '100vw',
    'image_widths': [360, 768, 1024, 1440, 1920],
    'class': 'w-full',
    'wrapper_class': 'w-full',
    'object-fit': 'cover',
    'object-position': 'center',
    'force_jpg': false,
    'usemap': '#image-map'
} %}

{{ image.render_picture('/chemin/vers/image.jpg', image_properties) }}
```


#### `Exemple avec le post thumbnail utilisant l'effet blur de la bibliothèque lazysizes`

```twig
{% import "components/content/media/image.html.twig" as image %}

{% set image_properties = {
    loading:'lazy',
    wrapper_class: 'w-full',
    use_lazysizes: true,
    loading_effect: 'blur',
    low_quality: post.thumbnail.src|resize(320, 240),
    class: 'w-full',
    width: post.thumbnail.width,
    height: post.thumbnail.height,
    alt: post.thumbnail.alt,
    webp: true,
    force_jpg: false,
    'object-fit': 'cover',
    image_widths: [360, 768, 1024, 1440, 1920],
    aspect_ratio: '16/9'
} %}

{{ image.render_picture(post.thumbnail.src, image_properties) }}
```

#### `Exemple avec le post thumbnail utiliser en tant que hero image`

```twig
{% import "components/content/media/image.html.twig" as image %}

{% set image_properties = {
    loading:'eager',
    fetchpriority: 'high'
    wrapper_class: 'w-full',
    class: 'w-full',
    width: post.thumbnail.width,
    height: post.thumbnail.height,
    alt: post.thumbnail.alt,
    webp: true,
    force_jpg: false,
    'object-fit': 'cover',
    image_widths: [360, 768, 1024, 1440, 1920],
    aspect_ratio: '16/9'
} %}

{{ image.render_picture(post.thumbnail.src, image_properties) }}
```

#### `Exemple loading lazy natif (sans bibliothèque)`

Par défaut, `loading = 'lazy'` et `use_lazysizes = false`donc il n'est pas utile de les définir.

```twig
{% import "components/content/media/image.html.twig" as image %}

{% set image_properties = {
    wrapper_class: 'w-full',
    class: 'w-full',
    width: post.thumbnail.width,
    height: post.thumbnail.height,
    alt: post.thumbnail.alt,
    webp: true,
    force_jpg: false,
    'object-fit': 'cover',
    image_widths: [360, 768, 1024, 1440, 1920],
    aspect_ratio: '16/9'
} %}

{{ image.render_picture(post.thumbnail.src, image_properties) }}
```

## Macros d'Image

### `render_sources`

La macro `render_sources` génère les balises `<source>` pour les différentes sources d'images à l'intérieur d'une balise `<picture>`.

#### Paramètres :

- `image_src` : Chemin de l'image source.
- `image_type` : Type d'image ('jpg', 'png', 'webp', etc.).
- `use_webp` : Booléen indiquant si les sources WebP doivent être générées.
- `force_jpg` : Booléen indiquant si les images WebP doivent être forcées à se convertir en JPG.
- `use_lazysizes` : Booléen indiquant si le chargement paresseux avec lazysizes doit être utilisé.
- `available_widths` : Liste des largeurs d'images disponibles.
- `aspect_ratio` : Rapport hauteur/largeur de l'image.
- `sizes` : Attribut `sizes` pour la balise `<source>`.

### `render_image_tag`

La macro `render_image_tag` génère la balise `<img>` avec les attributs d'image appropriés.

#### Paramètres :

- `image_src` : Chemin de l'image source.
- `image_format` : Format de l'image ('jpg', 'png', etc.).
- `force_jpg` : Booléen indiquant si les images WebP doivent être forcées à se convertir en JPG.
- `lazy_loading` : Type de chargement ('lazy', 'eager').
- `low_quality` : Image de basse qualité utilisée pour le chargement paresseux.
- `available_widths` : Liste des largeurs d'images disponibles.
- `aspect_ratio` : Rapport hauteur/largeur de l'image.
- `image_attributes` : Attributs de la balise `<img>`.

### `render_src`

La macro `render_src` génère l'attribut `src` ou `data-src` en fonction des paramètres fournis pour l'image.

#### Paramètres :

- `src` : Chemin de l'image source.
- `lazy_load` : Booléen indiquant si le chargement paresseux doit être utilisé.
- `low_quality` : Image de basse qualité utilisée pour le chargement paresseux.
- `use_webp` : Booléen indiquant si les sources WebP doivent être générées.
- `src_type` : Type d'attribut ('src' ou 'data-src').
- `force_jpg` : Booléen indiquant si les images WebP doivent être forcées à se convertir en JPG.
- `image_widths` : Liste des largeurs d'images disponibles.
- `aspect_ratio` : Rapport hauteur/largeur de l'image.

### `render_attributes`

La macro `render_attributes` génère les attributs HTML pour la balise `<img>` en fonction des propriétés spécifiées.

#### Paramètres :

- `arr` : Tableau associatif des attributs et de leurs valeurs.

Ces macros travaillent ensemble pour générer efficacement des balises `<picture>` avec des balises `<source>` pour différentes sources d'images, et insérer des balises `<img>` avec des attributs d'image appropriés. Les paramètres et les propriétés peuvent être ajustés en fonction de vos besoins spécifiques.


