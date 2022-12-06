import grapesjs from "grapesjs";
import fr from "../Config/translation_fr";
import { editorEndpoints } from "../Config/endpoints";
import {
    containerBlock, containerFluidBlock,
    h1, h2, h3, h4, h5, h6, image, text,
    OneCol,ThreeCols, TwoCols,
    templateImageText, templateImageTextFull,
    templateRowImageRowText, templateTextImage
} from "../Blocks/Blocks";
import { handleAssetRemove } from "../Utils/AssetManager/AssetManagerUtils";
import { AssetManagerService } from "./AssetManagerService";
import { Toast } from "../../Toast";
import "../Utils/CKEditorPlugin/index"
import { componentsTypesPlugin } from "../Utils/ComponentsTypesPlugin/componentsTypesPlugin";

export const initEditor = entrypointPath => {

    const pageId = window.location.pathname.split('/')[4]

    const editor = grapesjs.init({
        canvas: {
            styles: [{href: entrypointPath}]
        },
        selectorManager: {
            componentFirst: true,
        },
        container: "#gjs",
        height: "100vh",
        width: "auto",
        fromElement: true,
        i18n: {
            locale: 'fr',
            localeFallback: 'fr',
            messages: { fr }
        },
        mediaCondition: 'min-width',
        deviceManager: {
            devices: [
                {
                    name: 'Desktop',
                    width: '',
                },
                {
                    name: 'Tablet',
                    width: '600px',
                    widthMedia: '',
                },
                {
                    name: 'Mobile',
                    width: '400px', // this value will be used on canvas width
                    widthMedia: '', // this value will be used in CSS @media
                },

            ]
        },
        storageManager: {
            type: 'remote',
            autosave: true,
            stepsBeforeSave: 1,
            autoload: true,
            onStore: data => data,
            options: {
                remote: {
                    urlStore: editorEndpoints.page.item(pageId),
                    urlLoad: editorEndpoints.page.load(pageId),
                    contentTypeJson: true,

                    // Enrich the store call
                    onStore: (data, editor) => {
                        const pageHtml = editor.Pages.getAll().map(page => {
                            const component = page.getMainComponent();
                            return {
                                html: editor.getHtml({component}),
                                css: editor.getCss({component})
                            }
                        });
                        return {data, pageHtml};
                    },
                    onLoad: res => res.data
                }
            },
        },
        assetManager: {
            upload: editorEndpoints.image.collection,
            uploadName: 'files',
            params: {
                pageId
            }
        },
        blockManager: {
            blocks: [containerBlock, containerFluidBlock,
                h1, h2, h3, h4, h5, h6, image, text,
                OneCol,ThreeCols, TwoCols,
                templateImageText, templateImageTextFull,
                templateRowImageRowText, templateTextImage]
        },
        styleManager: {
          sectors: [
              {
                  name: 'typography',
                  properties: ['font-size', 'color', 'text-align'],
              },
              {
                  name: 'Image',
                  buildProps: ['object-fit'],
                  properties: [
                      {
                          name: 'Comportement de l\'image',
                          property: 'object-fit',
                          type: 'select',
                          defaults: 'cover',
                          toRequire: true,
                          list: [
                              { value: 'cover' },
                              { value: 'contain' }
                          ]
                      }
                  ]
              }
          ]
        },
        plugins: ['gjs-plugin-ckeditor5', componentsTypesPlugin],
        pluginsOpts: {
            'gjs-plugin-ckeditor5': {
                position: 'left',
                options: {
                    trackChanges: {},
                    toolbar: {
                        items: [
                            '|',
                            'bold',
                            'italic',
                            'link',
                            'bulletedList',
                            'numberedList',
                            '|',
                            'outdent',
                            'indent',
                            '|',
                            'undo',
                            'redo'
                        ]
                    },
                    language: 'fr',
                    licenseKey: ''
                }
            }
        }
    });

    editor.Panels.removePanel('options')
    editor.Panels.getButton('views', 'open-blocks').set('active', true)

    // désactive l'affichage par défaut du mode responsive
    editor.getConfig().showDevices = false;
    editor.Panels.addPanel({
        id: 'devices', buttons: [
            {
                id: "set-device-desktop",
                command: e => e.setDevice("Desktop"),
                className: "fa fa-desktop",
                active: 1,
                attributes: {
                    title: 'Aperçu en version Ordinateur'
                }
            },
            {
                id: "set-device-tablet",
                command: e => e.setDevice("Tablet"),
                className: "fa fa-tablet-alt",
                attributes: {
                    title: 'Aperçu en version Tablette'
                }
            },
            {
                id: "set-device-mobile",
                command: e => e.setDevice("Mobile"),
                className: "fa fa-mobile-alt",
                attributes: {
                    title: 'Aperçu en version Mobile'
                }
            }
        ]
    });


    editor.on('asset:open', () => {
        handleAssetRemove()
    })

    editor.on('asset:upload:end', () => {
        handleAssetRemove()
    })


    editor.on('asset:remove', async (asset) => {
        try {
            const assetId = asset.attributes.id;
            const res = await AssetManagerService.remove(assetId)
            if (res.status === 200) Toast.success('L\'image a bien été supprimée !')

        } catch (e) {
            // si erreur API, l'asset ne sera pas supprimée du serveur
            // mais correctement supprimée dans l'éditeur
            Toast.error('Une erreur est survenue lors de la suppression.')
        }
    })

    return editor

}