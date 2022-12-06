export const componentsTypesPlugin = editor => {
    const domc = editor.DomComponents;

    domc.addType('image', {
        model: {
            defaults: {
                'stylable-require': ['object-fit'],
                unstylable: ['font-size', 'color', 'text-align'],
            }
        }
    })

    domc.addType('default', {
        model: {
            defaults: {
                stylable: []
            }
        }
    })

    domc.addType('wrapper', {
        model: {
            defaults: {
                stylable: []
            }
        }
    })
}