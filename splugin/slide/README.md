# Libreria Carrusel De Imagenes

Muchas veces queremos agregar carruseles de imagenes a nuestra pagina web, sin embargo, crearlos desde 0 toma demasiado tiempo y se deben tener conocimientos de css y javascript. Existen frameworks como bootstrap y materialize, que incluyen sus propios carruseles, pero aún así necesitamos más opciones. Es por ello que hemos creado esta librería con diferentes opciones de carruseles para que puedas elegir, el que mejor se adapta a tu pagina web.

![libreria-carrusel-preview](https://user-images.githubusercontent.com/32299783/37909852-7068b876-30e2-11e8-88b6-378d76ee1a4c.jpg)

## Desarrollado para 

[Laboratoria](http://www.laboratoria.la/)

## Aspectos Técnicos

<img src='https://img.shields.io/badge/Jquery-3.3.1-blue.svg'>
<img src='https://img.shields.io/badge/Test-90%25-brightgreen.svg'>
<img src='https://img.shields.io/badge/Mocha-5.0.0-yellow.svg'>
<img src='https://img.shields.io/badge/Chai-4.1.2-yellowgreen.svg'>
<img src='https://img.shields.io/badge/JsDom-11.6.2-orange.svg'>
<img src='https://img.shields.io/badge/Font--Awesome-4.7.0-brightgreen.svg'>


## [Demo Libreria Jquery](https://nadiaqn.github.io/libreria-carrusel/)


## Instrucciones de uso

Para poder usar la libreria debes descargar este repositorio, para ello usa este comando en tu terminal:

```github
    git clone https://github.com/NadiaQN/libreria-carrusel
```



- Carrusel Automatico

    Para hacer uso de este carrusel solo debes tener esta estructura en tu html:

    ```html
    <div id='auto-carusel'>
        <div><img src='link/ubicacionDeTuImagen'></div>
        <!-- Aquí puedes agregar todas las imagenes que quieras -->
    </div>
    ```
    Y debes agregar este este script:

    ```html
    <script src='dist/carruselAutomatic-bundle.js'>
    ```

- Carrusel con iconos de navegación

    Para usar este carrusel debes tener esta estructura en tu html:

    ```html
    <ul class='list-images'>
        <!-- Aquí irán tus imagenes -->
        <li><img src='link/ubicacionDeTuImagen'></li>
    </ul>
    <!-- Aquí estaran los iconos de navegación, debes copiar esta sección tal cual y como esta-->
    <ol class='navegation'>
        <li class='left iconsDefaultColor'><span class='fa fa-chevron-left'></span></li>
        <li class='right iconsDefaultColor'><span class='fa fa-chevron-right'></span></li>
    </ol>
    ```

    Este carrusel cuenta con 2 clases CSS para que puedas usar el color que quieras en los iconos de navegación:

    ```css
    .iconsDefaultColor {
        color: /*color por defecto de los iconos*/;
    }
    .iconsNavegationColor {
        color: /*color cuando una imagen esta seleccionada*/;
    }
    ```

    Finalmente debes agregar el script a tu html:

    ```html
    <script src='dist/carruselNavegation-bundle.js'>
    ```

- Carrusel con texto

    Para usar este carrusel debes seguir esta estructura en tu html:

    ```html
    <div class='caruselV3'>
        <!-- Cada imagen debe seguir esta estructura -->
        <div class='carousel'>
            <img src='link/ubicacionDeTuImagen'>
            <div class='text'>
                <!-- Aquí va el texto que quieras-->
            </div>
        </div>
    </div>
    ```

    Y por último debes agregar el script:

    ```html
    <script src='dist/carruselText-bundle.js'>
    ```

La ruta de los script puede diferir dependiendo de donde se encuentre la carpeta dist, pero dentro de ella siempre estaran los archivos js necesarios para el funcionamiento de los carruseles, por lo que puedes ajustar la ruta según tus necesidades.




## Colaboradores

Maria Jose Solar [@marasolarp](https://github.com/marasolarp)

Nadia Quezada [@NadiaQN](https://github.com/NadiaQN)
