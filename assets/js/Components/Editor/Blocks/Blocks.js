import {
    generateHeadingBlock,
    defaultRowAttr,
    generateColumnAttr, generateContainerAttr
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
    media: `<svg viewBox="0 0 24 24">
        <path fill="currentColor" d="M18.5,4L19.66,8.35L18.7,8.61C18.25,7.74 17.79,6.87 17.26,6.43C16.73,6 16.11,6 15.5,6H13V16.5C13,17 13,17.5 13.33,17.75C13.67,18 14.33,18 15,18V19H9V18C9.67,18 10.33,18 10.67,17.75C11,17.5 11,17 11,16.5V6H8.5C7.89,6 7.27,6 6.74,6.43C6.21,6.87 5.75,7.74 5.3,8.61L4.34,8.35L5.5,4H18.5Z" />
      </svg>`,
    category: 'Contenu',
    content: '<div data-gjs-type="text" data-gjs-name="Texte">Votre texte ici</div>',
    activate: true
}

const image = {
    id: 'image',
    label: 'Image',
    media: `<svg viewBox="0 0 24 24">
        <path fill="currentColor" d="M21,3H3C2,3 1,4 1,5V19A2,2 0 0,0 3,21H21C22,21 23,20 23,19V5C23,4 22,3 21,3M5,17L8.5,12.5L11,15.5L14.5,11L19,17H5Z" />
      </svg>`,
    // Select the component once it's dropped
    select: true,
    content: {
        tagName: 'div',
        draggable: true,
        highlightable: false,
        selectable: false,
        hoverable: false,
        classes: ['text-center'],
        components: [
            {
                type: 'image',
                classes: ['img-fluid']
            }
        ]
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
    media: `<svg viewBox="0 0 24 24">
        <path fill="currentColor" d="M2 20h20V4H2v16Zm-1 0V4a1 1 0 0 1 1-1h20a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1Z"/>
      </svg>`,
    category: 'Colonnes',
    content: `<div ${defaultRowAttr}>
    <div ${attrCol}></div>
    </div>`
}

const attrColTwo = generateColumnAttr(2)
const TwoCols = {
    label: '2 colonnes',
    media: `<svg viewBox="0 0 23 24">
        <path fill="currentColor" d="M2 20h8V4H2v16Zm-1 0V4a1 1 0 0 1 1-1h8a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1ZM13 20h8V4h-8v16Zm-1 0V4a1 1 0 0 1 1-1h8a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1h-8a1 1 0 0 1-1-1Z"/>
      </svg>`,
    category: 'Colonnes',
    content: `<div ${defaultRowAttr}>
    <div ${attrColTwo}></div>
    <div ${attrColTwo}></div>
    </div>`
}

const attrColThree = generateColumnAttr(3)
const ThreeCols = {
    label: '3 colonnes',
    media: `<svg viewBox="0 0 23 24">
        <path fill="currentColor" d="M2 20h4V4H2v16Zm-1 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1ZM17 20h4V4h-4v16Zm-1 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1ZM9.5 20h4V4h-4v16Zm-1 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1Z"/>
      </svg>`,
    category: 'Colonnes',
    content: `<div ${defaultRowAttr}>
    <div ${attrColThree}></div>
    <div ${attrColThree}></div>
    <div ${attrColThree}></div>
    </div>`
}

const attrContainer = generateContainerAttr()
const containerBlock = {
    label: 'Conteneur',
    category: 'Conteneurs',
    content: `<div ${attrContainer}></div>`
}

const attrContainerFluid = generateContainerAttr('fluid')
const containerFluidBlock = {
    label: 'Conteneur Fluid',
    category: 'Conteneurs',
    content: `<div ${attrContainerFluid}></div>`
}

const videoBlock = {
    label: 'Vidéo',
    category: 'Contenu',
    media: `<svg viewBox="0 0 24 24">
        <path fill="currentColor" d="M10,15L15.19,12L10,9V15M21.56,7.17C21.69,7.64 21.78,8.27 21.84,9.07C21.91,9.87 21.94,10.56 21.94,11.16L22,12C22,14.19 21.84,15.8 21.56,16.83C21.31,17.73 20.73,18.31 19.83,18.56C19.36,18.69 18.5,18.78 17.18,18.84C15.88,18.91 14.69,18.94 13.59,18.94L12,19C7.81,19 5.2,18.84 4.17,18.56C3.27,18.31 2.69,17.73 2.44,16.83C2.31,16.36 2.22,15.73 2.16,14.93C2.09,14.13 2.06,13.44 2.06,12.84L2,12C2,9.81 2.16,8.2 2.44,7.17C2.69,6.27 3.27,5.69 4.17,5.44C4.64,5.31 5.5,5.22 6.82,5.16C8.12,5.09 9.31,5.06 10.41,5.06L12,5C16.19,5 18.8,5.16 19.83,5.44C20.73,5.69 21.31,6.27 21.56,7.17Z" />
      </svg>`,
    content: {
        type: 'video',
        style: {
            height: '350px',
            width: '615px'
        }
    }
}

const mapBlock = {
    label: 'Carte',
    category: 'Contenu',
    media: `<svg viewBox="0 0 24 24">
        <path fill="currentColor" d="M20.5,3L20.34,3.03L15,5.1L9,3L3.36,4.9C3.15,4.97 3,5.15 3,5.38V20.5A0.5,0.5 0 0,0 3.5,21L3.66,20.97L9,18.9L15,21L20.64,19.1C20.85,19.03 21,18.85 21,18.62V3.5A0.5,0.5 0 0,0 20.5,3M10,5.47L14,6.87V18.53L10,17.13V5.47M5,6.46L8,5.45V17.15L5,18.31V6.46M19,17.54L16,18.55V6.86L19,5.7V17.54Z" />
      </svg>`,
    content: {
        type: 'map',
        style: {
            height: '350px',
            width: '615px'
        }
    }
}

// const heading = {
//     label: 'heading',
//     category: 'Titres',
//     type: 'text-heading',
//     content: 'mon titre superbe',
//     tagName: 'h1'
// }

export { h1, h2, h3, h4, h5, h6, text, image,
    OneCol, TwoCols, ThreeCols,
    templateTextImage, templateImageText, templateRowImageRowText, templateImageTextFull,
    containerBlock, containerFluidBlock, videoBlock, mapBlock
}