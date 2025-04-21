class ThemeManager {
    constructor(themePageType) {
        this.lang = getCurrentLangFromUrl();
        this.contentPagePreview = document.getElementById('flowfinder-content-page');
        // Charger des thèmes spécifiques
        if (document.getElementById('loadDefaultTheme')) {
            document.getElementById('loadDefaultTheme').addEventListener('click', () => {
                this.loadTheme();
            });
        }

        if (document.getElementById('savePersonaliseeTheme')) {
            document.getElementById('savePersonaliseeTheme').addEventListener('click', () => {
                this.saveThemeInDatabase();
            });
        }

        this.themePageType = themePageType;

    }

    // Charger un thème spécifique
    loadTheme(nomtheme = 'default', idProprietaireTheme) {
        const xhttp = new XMLHttpRequest();
        var form_data = new FormData();
        form_data.append('themepage_type', this.themePageType);
        form_data.append('themenom', nomtheme);
        // Vérifier si le chemin contient pour voir si on est en public "/p/"

        if (typeof (idProprietaireTheme) != "undefined") {
            form_data.append('themepublic', "true");
            form_data.append('id_proprietaire_theme', idProprietaireTheme);
        } else {
            form_data.append('themepublic', "false");
            form_data.append('id_proprietaire_theme', -1);
        }
        
        xhttp.open("POST", `/${this.lang}/theme/charge_theme`, true);
        xhttp.send(form_data);
        xhttp.onload = () => {
            if (xhttp.status === 200) {
                try {
                    const response = JSON.parse(xhttp.responseText);
                    var datajson = response.theme_css;
                    if (typeof (idProprietaireTheme) != "undefined") {
                        this.LoadStylesFromJsonPublic(datajson);
                    } else {
                        this.LoadStylesFromJsonAdmin(datajson);
                    }
                } catch (error) {
                    console.log(error);
                }
            }
        };
    }

    LoadStylesFromJsonAdmin(jsonData) {
        const styles = JSON.parse(jsonData);
        for (const elementId in styles) {
            if (styles.hasOwnProperty(elementId)) {
                const styleProperties = styles[elementId];

                for (const property in styleProperties) {
                    if (styleProperties.hasOwnProperty(property)) {
                        // Construire l'ID de l'input correspondant
                        let inputIdPrefix = '';

                        // Trouver le bon préfixe en fonction de la propriété de style
                        if (property === 'color') {
                            inputIdPrefix = 'inputColor-';
                        } else if (property === 'backgroundColor') {
                            inputIdPrefix = 'inputBackgroundColor-';
                        } else if (property === 'borderColor') {
                            inputIdPrefix = 'inputBorderColor-';
                        } else if (property === 'borderRadius') {
                            inputIdPrefix = 'inputRange-';
                        }

                        const inputElement = document.getElementById(inputIdPrefix + elementId);

                        if (inputElement) {
                            // Charger la valeur dans l'input
                            inputElement.value = styleProperties[property];
                        }
                    }
                }
            }
        }
        //recharger le preview
        this.PreviewInputTheme();
    }

    // Sauvegarder le thème dans la base de données
    saveThemeInDatabase() {
        let stylecss = this.SerializeStyleForm();
        const xhttp = new XMLHttpRequest();
        var form_data = new FormData();
        form_data.append('themepage_type', this.themePageType);
        form_data.append('stylecss', JSON.stringify(stylecss));

        xhttp.open("POST", `/${this.lang}/theme/save_theme`, true);
        xhttp.send(form_data);
        xhttp.onload = function () {
            if (xhttp.status === 200) {
                //console.log("Données envoyées avec succès :", xhttp.responseText);
            } else {
                //console.log("Erreur lors de l'envoi des données :", xhttp.statusText);
            }
        };
    }

    RechercheInputs() {
        // Sélectionner tous les éléments ayant un id qui commence par "inputColor-" ou "inputRange-"
        const inputsColor = document.querySelectorAll('[id^="inputColor-"]');
        const inputsBackgroundColor = document.querySelectorAll('[id^="inputBackgroundColor-"]');
        const inputsBorderColor = document.querySelectorAll('[id^="inputBorderColor-"]');
        const inputsRange = document.querySelectorAll('[id^="inputRange-"]');
        const inputsContent = document.querySelectorAll('[id^="inputContent-"]');
        const inputsPersonnalise = document.querySelector('#inputCssPersonnalise');
        // Convertir NodeList en tableau
        const allInputs = [...inputsColor, ...inputsBackgroundColor, ...inputsBorderColor, ...inputsRange, ...inputsContent, ...(inputsPersonnalise ? [inputsPersonnalise] : [])];
        return allInputs;
    }

    RechercheContents() {
        // Sélectionner tous les éléments ayant un id qui commence par "inputColor-" ou "inputRange-"
        const contents = document.querySelectorAll('[id^="flowfinder-content-"]');
        // Convertir NodeList en tableau
        const allContents = [...contents];
        return allContents;
    }

    SerializeStyleForm() {
        const allInputs = this.RechercheInputs();
        let styles = {};
        allInputs.forEach(input => {
            let idPrefix = '';
            let targetContentPrefix = '';
            let styleProperty = '';

            // Détection des préfixes d'ID pour appliquer le bon style
            if (input.id.startsWith('inputColor-')) {
                idPrefix = 'inputColor-';
                styleProperty = 'color'; // Propriété CSS à appliquer
            } else if (input.id.startsWith('inputBackgroundColor-')) {
                idPrefix = 'inputBackgroundColor-';
                styleProperty = 'backgroundColor'; // Propriété CSS à appliquer
            } else if (input.id.startsWith('inputBorderColor-')) {
                idPrefix = 'inputBorderColor-';
                styleProperty = 'borderColor'; // Propriété CSS à appliquer
            } else if (input.id.startsWith('inputRange-')) {
                idPrefix = 'inputRange-';
                styleProperty = 'borderRadius'; // Propriété CSS à appliquer
            } else if (input.id.startsWith('inputContent-')) {
                styleProperty = 'inputContent';
            } else if (input.id.startsWith('inputCssPersonnalise')) {
                styleProperty = 'inputCssPersonnalise'; // Propriété CSS à appliquer
            }

            // Récupérer l'élément cible associé à l'input via son préfixe d'ID
            const targetElementId = input.id.substring(idPrefix.length);
            //console.log(targetElementId);
            const targetElement = document.getElementById(idPrefix + targetElementId);
            //console.log(targetElement);
            if (targetElement) {
                // Stocker les styles de l'élément dans l'objet `styles`

                if (!styles[targetElementId]) {
                    styles[targetElementId] = {}; // Créer l'objet si non existant
                }
                // Appliquer et capturer le style spécifique (color, backgroundColor, etc.)
                styles[targetElementId][styleProperty] = input.value;
            }
        })
        return JSON.stringify(styles);
    }

    PreviewInputTheme() {
        const allInputs = this.RechercheInputs();
        allInputs.forEach(input => {
            // Extraire la partie "xxx" de l'id (après le préfixe)
            let idPrefix = '';
            let targetContentPrefix = '';
            if (input.id.startsWith('inputColor-')) {
                idPrefix = 'inputColor-';
                targetContentPrefix = 'flowfinder-content-';
            } else if (input.id.startsWith('inputBackgroundColor-')) {
                idPrefix = 'inputBackgroundColor-';
                targetContentPrefix = 'flowfinder-content-';
            } else if (input.id.startsWith('inputBorderColor-')) {
                idPrefix = 'inputBorderColor-';
                targetContentPrefix = 'flowfinder-content-';
            } else if (input.id.startsWith('inputRange-')) {
                idPrefix = 'inputRange-';
                targetContentPrefix = 'flowfinder-content-';
            } else if (input.id.startsWith('inputContent-')) {
                idPrefix = 'inputContent-';
                targetContentPrefix = 'flowfinder-content-';
            }
            // Trouver le bon identifiant "xxx"
            const xxx = input.id.substring(idPrefix.length); // extrait "xxx"

            // Cibler les éléments content-xxx correspondant soit par id, soit par class
            let contentElements = document.querySelectorAll('#' + targetContentPrefix + xxx + ', .' + targetContentPrefix + xxx);

            // Si c'est un input de couleur
            if (input.id.startsWith('inputColor-')) {
                input.addEventListener('input', function () {
                    let color = input.value;
                    contentElements.forEach(element => {
                        element.style.color = color;
                    });
                });
                let color = input.value;
                contentElements.forEach(element => {
                    element.style.color = color;
                });
            }

            // Si c'est un input de background couleur
            if (input.id.startsWith('inputBackgroundColor-')) {
                input.addEventListener('input', function () {
                    let color = input.value;
                    contentElements.forEach(element => {
                        element.style.backgroundColor = color;
                    });
                });
                let color = input.value;
                contentElements.forEach(element => {
                    element.style.backgroundColor = color;
                });
            }

            // Si c'est un input de border couleur
            if (input.id.startsWith('inputBorderColor-')) {
                input.addEventListener('input', function () {
                    let color = input.value;
                    contentElements.forEach(element => {
                        element.style.borderColor = color;
                    });
                });
                let color = input.value;
                contentElements.forEach(element => {
                    element.style.borderColor = color;
                });
            }

            // Si c'est un input de range
            if (input.id.startsWith('inputRange-')) {
                input.addEventListener('input', function () {
                    let size = input.value;
                    contentElements.forEach(element => {
                        element.style.borderRadius = size + 'rem'; // Exemple pour border-radius
                    });
                });
                let size = input.value;
                contentElements.forEach(element => {
                    element.style.borderRadius = size + 'rem'; // Exemple pour border-radius
                });
            }

            // Si c'est un input content
            if (input.id.startsWith('inputContent-')) {
                contentElements.forEach(element => {
                    element.textContent = input.value; // Remplacement du texte
                });
            }

            // Si Css Personnalise
            if (input.id.startsWith('inputCssPersonnalise')) {
                const input = document.querySelector('#inputCssPersonnalise');
                // Sélectionner l'élément 'flowfinder-content-page'
                let contentPage = document.getElementById('flowfinder-content-page');

                // Vérifier si l'élément existe
                if (contentPage) {
                    // Créer un nouvel élément <style>
                    const style = document.createElement('style');
                    // Mettre la valeur de l'input dans le contenu de l'élément <style>
                    style.innerHTML = `${input.value}`;
                    // Ajouter le <style> juste avant l'élément 'flowfinder-content-page'
                    contentPage.parentNode.insertBefore(style, contentPage);
                }
            }
        });
    }

    LoadStylesFromJsonPublic(datajson) {
        // Parcours de chaque clé dans le JSON
        datajson = JSON.parse(datajson);
        for (const key in datajson) {
            if (datajson.hasOwnProperty(key)) {
                const styles = datajson[key];
                // Recherche par ID
                const idSelector = `#flowfinder-content-${key}`;
                const idElement = document.querySelector(idSelector);
                if (idElement) {
                    this.ApplyStylesPublic(idElement, styles);
                }

                // Recherche par classe
                const classSelector = `.flowfinder-content-${key}`;
                const classElements = document.querySelectorAll(classSelector);
                classElements.forEach(element => {
                    this.ApplyStylesPublic(element, styles);
                });


                // Si c'est la Css Personnalisé
                if (datajson[key].inputCssPersonnalise) {
                    console.log(datajson[key].inputCssPersonnalise);
                    const style = document.createElement('style');
                    style.innerHTML = `${datajson[key].inputCssPersonnalise}`;
                    document.head.appendChild(style);
                }

            }
        }

    }

    // Fonction pour appliquer les styles à un élément
    ApplyStylesPublic(element, styles) {
        for (const property in styles) {
            if (styles.hasOwnProperty(property)) {
                if (property === 'borderRadius') {
                    element.style.borderRadius = styles[property] + 'rem';;
                } else if (property === 'borderColor') {
                    element.style.borderColor = styles[property];
                    //element.style.borderStyle = 'solid';
                } else {
                    element.style[property] = styles[property];
                }
            }
        }
    }

}