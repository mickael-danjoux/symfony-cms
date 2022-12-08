import { Toast } from "../../../Toast";

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
                stylable: false
            }
        }
    })

    domc.addType('text', {
        model: {
            defaults: {
                stylable: ['font-size', 'color', 'text-align']
            }
        }
    })

    domc.addType('wrapper', {
        model: {
            defaults: {
                stylable: false

            }
        }
    })

    domc.addType('video', {
        extendFn: ['updateTraits'],
        model: {
            defaults: {
                stylable: false
            },
            init() {
                this.addUrlTrait()
                this.addBtnTrait()
                this.removeUnusedTraits()
                this.set('provider', 'yt')
                this.set('controls', true)
            },
            updateTraits() {
                this.addUrlTrait()
                this.addBtnTrait()
                this.removeUnusedTraits()
            },
            removeUnusedTraits() {
                this.removeTrait(['provider', 'videoId', 'src', 'poster', 'rel', 'modestbranding', 'controls'])
            },
            addUrlTrait() {
                if (!this.getTrait('data-url')) {
                    this.addTrait({
                        type: 'text',
                        name: 'data-url',
                        label: 'URL Youtube'
                    })
                }
            },
            addBtnTrait() {
                if (!this.getTrait('btn-video')) {
                    this.addTrait({
                        type: 'button',
                        name: 'btn-video',
                        text: 'Valider',
                        command: () => this.updateVideoId(this.getTrait('data-url').attributes.value)
                    })
                }
            },
            updateVideoId(url) {
                const regex = /^.*(?:(?:youtu\.be\/|v\/|vi\/|u\/\w\/|embed\/|shorts\/)|(?:(?:watch)?\?v(?:i)?=|\&v(?:i)?=))([^#\&\?]*).*/;
                const videoId = url.match(regex)
                if (videoId) {
                    this.set('videoId', videoId[1] ?? '')
                } else {
                    Toast.warning("L'URL Youtube n'est pas valide, impossible de mettre à jour la vidéo. \n Veuillez entrez l'URL complète de la vidéo.")
                }
            }
        }
    })

    domc.addType('map', {
        model: {
            defaults: {
                stylable: false,

            }
        }
    })
}