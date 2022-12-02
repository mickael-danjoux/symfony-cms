import {
    generateHeadingBlock,
    defaultRowAttr,
    generateColumnAttr
} from "../Utils/Blocks/BlocksUtils";

const h1 = generateHeadingBlock('h1', 1)
const h2 = generateHeadingBlock('h2', 2)
const h3 = generateHeadingBlock('h3', 3)
const h4 = generateHeadingBlock('h4', 4)
const h5 = generateHeadingBlock('h5', 5)
const h6 = generateHeadingBlock('h6', 6)

const text = {
    id: 'text',
    label: 'Texte',
    category: 'Contenu',
    content: '<div data-gjs-type="text" data-gjs-name="Texte">Votre texte ici</div>'
}

const image = {
    id: 'image',
    label: 'Image',
    // Select the component once it's dropped
    select: true,
    // You can pass components as a JSON instead of a simple HTML string,
    // in this case we also use a defined component type `image`
    content: {
        type: 'image',
        classes: ['img-fluid']
    },
    // This triggers `active` event on dropped components and the `image`
    // reacts by opening the AssetManager
    activate: true,
    category: 'Contenu',
}

const templateTextImage = {
    label: 'Texte + Image',
    category: 'Templates',
    content: {
        tagName: 'div',
        draggable: true,
        highlightable: false,
        selectable: false,
        hoverable: false,
        classes: ['row'],
        components: [
            {
                tagName: 'div',
                highlightable: false,
                selectable: false,
                hoverable: false,
                classes: ['col-12', 'col-md-6'],
                components: [
                    {
                        type: 'text',
                        content: 'Votre texte ici'
                    }
                ]
            },
            {
                tagName: 'div',
                highlightable: false,
                selectable: false,
                hoverable: false,
                classes: ['col-12', 'col-md-6'],
                components: [
                    {
                        type: 'image',
                        classes: ['img-fluid']
                    }
                ]
            },
        ]
    }
}

const templateImageText = {
    label: 'Image + Texte',
    category: 'Templates',
    content: {
        tagName: 'div',
        draggable: true,
        highlightable: false,
        selectable: false,
        hoverable: false,
        classes: ['row'],
        components: [
            {
                tagName: 'div',
                highlightable: false,
                selectable: false,
                hoverable: false,
                classes: ['col-12', 'col-md-6'],
                components: [
                    {
                        type: 'image',
                        classes: ['img-fluid']
                    }
                ]
            },
            {
                tagName: 'div',
                highlightable: false,
                selectable: false,
                hoverable: false,
                classes: ['col-12', 'col-md-6'],
                components: [
                    {
                        type: 'text',
                        content: 'Votre texte ici'
                    }
                ]
            }
        ]
    }
}
const templateImageTextFull = {
    label: 'Image pleine largeur + texte',
    category: 'Templates',
    content: {
        tagName: 'div',
        draggable: true,
        highlightable: false,
        selectable: false,
        hoverable: false,
        classes: ['row'],
        components: [
            {
                tagName: 'div',
                highlightable: false,
                selectable: false,
                hoverable: false,
                classes: ['col-12'],
                components: [
                    {
                        type: 'image',
                        classes: ['img-fluid']
                    }
                ]
            },
            {
                tagName: 'div',
                highlightable: false,
                selectable: false,
                hoverable: false,
                classes: ['col-12'],
                components: [
                    {
                        type: 'text',
                        content: 'Votre texte ici'
                    }
                ]
            }
        ]
    }
}

const templateRowImageRowText = {
    label: '2 images 2 textes',
    category: 'Templates',
    content: {
        tagName: 'div',
        draggable: true,
        highlightable: false,
        selectable: false,
        hoverable: false,
        classes: ['row'],
        components: [
            {
                tagName: 'div',
                highlightable: false,
                selectable: false,
                hoverable: false,
                classes: ['col-12', 'col-md-6'],
                components: [
                    {
                        type: 'image',
                        classes: ['img-fluid']
                    }
                ]
            },
            {
                tagName: 'div',
                highlightable: false,
                selectable: false,
                hoverable: false,
                classes: ['col-12', 'col-md-6'],
                components: [
                    {
                        type: 'image',
                        classes: ['img-fluid']
                    }
                ]
            },
            {
                tagName: 'div',
                highlightable: false,
                selectable: false,
                hoverable: false,
                classes: ['col-12', 'col-md-6'],
                components: [
                    {
                        type: 'text',
                        content: 'Votre texte ici'
                    }
                ]
            },
            {
                tagName: 'div',
                highlightable: false,
                selectable: false,
                hoverable: false,
                classes: ['col-12', 'col-md-6'],
                components: [
                    {
                        type: 'text',
                        content: 'Votre texte ici'
                    }
                ]
            }
        ]
    }
}

const attrCol = generateColumnAttr()
const OneCol = {
    label: '1 colonne',
    category: 'Colonnes',
    content: `<div ${defaultRowAttr}>
    <div ${attrCol}></div>
    </div>`
}

const attrColTwo = generateColumnAttr(2)
const TwoCols = {
    label: '2 colonnes',
    category: 'Colonnes',
    content: `<div ${defaultRowAttr}>
    <div ${attrColTwo}></div>
    <div ${attrColTwo}></div>
    </div>`
}

const attrColThree = generateColumnAttr(3)
const ThreeCols = {
    label: '3 colonnes',
    category: 'Colonnes',
    content: `<div ${defaultRowAttr}>
    <div ${attrColThree}></div>
    <div ${attrColThree}></div>
    <div ${attrColThree}></div>
    </div>`
}

export { h1, h2, h3, h4, h5, h6, text, image,
    OneCol, TwoCols, ThreeCols,
    templateTextImage, templateImageText, templateRowImageRowText, templateImageTextFull
}