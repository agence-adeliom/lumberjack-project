# Overlay

Overlay components are used to provide emphasis on a particular element or an action of the user.

In this folder you can have :

-   Backdrop
-   Dialog
-   ...

## Modal usage

Côté template, il y a 2 étapes à suivre :

-   La première consiste à mettre en place le bouton qui va déclencher l'ouverture de la modal. Pour cela, il faut ajouter un attribut `data-micromodal-trigger` sur le bouton et lui donner comme valeur l'ID de la modal à ouvrir. Exemple :

```html
<button
    data-micromodal-trigger="modal-fade"
    class="modal-open btn btn--primary"
    aria-label="Ouverture de la modal newsletter"
>
    Modal Fade Opener
</button>
```

-   La seconde consiste à importer le composant `components/overlay/modal.html.twig` avec comme paramètre modalID (ID de la modal) et le contenu de la modal dans le block content. Exemple :

```html
{% embed "components/overlay/modal.html.twig" with {obj: { modalID:
'modal-fade', effect: 'slide-fade', } } %} {% block content %} Modal content {%
endblock %} {% endembed %}
```

3 effets d'apparition sont prédéfinis : `slide-fade`, `slide-top`, `slide-bottom`. (Ajustables dans le fichier `web/app/themes/adeliom/assets/components/utils/modals/index.pcss`)

Il est important que l'ID des modal soit unique sur la page.
