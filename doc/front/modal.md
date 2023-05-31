# Utilisation des modals

Le starter Wordpress propose par défaut un système de modal basé sur [Micromodal](https://micromodal.vercel.app/).

## Utilisation

Une configuration de base a été mise en place dans le dossier `web/app/themes/adeliom/assets/components/utils/modals`. 

Le fichier n'est pas importé par défaut dans le starter, il faut donc l'importer dans vos composants/page qui nécessitent une modal.

Côté template, il y a 2 étapes à suivre :

- La première consiste à mettre en place le bouton qui va déclencher l'ouverture de la modal. Pour cela, il faut ajouter un attribut `data-micromodal-trigger` sur le bouton et lui donner comme valeur l'ID de la modal à ouvrir. Exemple : 
```html
 <button
      data-micromodal-trigger="modal-fade"
      class="modal-open btn btn--primary"
      aria-label="Ouverture de la modal newsletter"
  >
      Modal Fade Opener
  </button>
```

- La seconde consiste à importer le composant `components/overlay/modal.html.twig` avec comme paramètre modalID (ID de la modal) et le contenu de la modal dans le block content. Exemple : 
```html
{% embed "components/overlay/modal.html.twig" with {obj: {
    modalID: 'modal-fade',
    effect: 'slide-fade',
    }
} %}
    {% block content %}
        Modal content
    {% endblock %}
{% endembed %}
```

3 effets d'apparition sont prédéfinis : `slide-fade`, `slide-top`, `slide-bottom`. (Ajustables dans le fichier `web/app/themes/adeliom/assets/components/utils/modals/index.pcss`)

Il est important que l'ID des modal soit unique sur la page.