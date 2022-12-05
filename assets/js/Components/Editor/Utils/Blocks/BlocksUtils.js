const generateColumnAttr = colNumber => {
    const colBlockClone = {...defaultColumnBlock}
    if (colNumber === 2) colBlockClone.class = [...defaultColumnBlock.class, 'col-md-6']
    if (colNumber === 3) colBlockClone.class = [...defaultColumnBlock.class, 'col-md-4']
    return objToAttrString(colBlockClone)
}

const objToAttrString = blockDefinition => {
    let attrString = "";

    for (const key in blockDefinition) {
        if (key === 'class') {
            const joinedClasses = blockDefinition[key].join(' ')
            attrString += `${key}='${joinedClasses}'` + ' ';
        } else {
            attrString += `${dataAttrPrefix}-${key}='${blockDefinition[key]}'` + ' ';
        }
    }
    return attrString;
}

const generateHeadingBlock = (htmlTag, level) => {
    return {
        tagName: htmlTag,
        label: htmlTag.toUpperCase(),
        type: 'text',
        content: `<${htmlTag} data-gjs-name="${htmlTag.toUpperCase()}">Titre de niveau ${level}</${htmlTag}>`,
        category: 'Titres',
        attributes: {
            title: `Ajoute un titre de niveau ${level}`
        }
    }
}

const generateContainerAttr = type => {
    const containerBlockClone = {...defaultContainerBlock}

    if (type === 'fluid') {
        containerBlockClone.name = 'Conteneur Fluid'
        containerBlockClone.class.length = 0
        containerBlockClone.class = ['container-fluid']
    }

    return objToAttrString(containerBlockClone)
}

const dataAttrPrefix = 'data-gjs'

const defaultRowBlock = {
    name: 'Row',
    draggable: true,
    highlightable: false,
    selectable: false,
    hoverable: false,
    class: ['row', 'p-3']
}
const defaultColumnBlock = {
    name: 'Colonne',
    draggable: true,
    highlightable: true,
    selectable: true,
    hoverable: true,
    class: ['col-12']
}

const defaultContainerBlock = {
    name: 'Conteneur',
    draggable: true,
    highlightable: true,
    selectable: true,
    hoverable: true,
    class: ['container']
}



const defaultRowAttr = objToAttrString(defaultRowBlock)
const defaultColAttr = objToAttrString(defaultColumnBlock)

export { defaultRowBlock, defaultColumnBlock, defaultRowAttr, defaultColAttr, generateHeadingBlock, objToAttrString, generateColumnAttr, generateContainerAttr }