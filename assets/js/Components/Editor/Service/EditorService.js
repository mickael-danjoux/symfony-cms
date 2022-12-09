import grapesjs from "grapesjs";
import customCodePlugin from 'grapesjs-custom-code';
import fr from "../Config/translation_fr";
import { editorEndpoints } from "../Config/endpoints";
import {
    containerBlock, containerFluidBlock,
    h1, h2, h3, h4, h5, h6, image, text,
    OneCol, ThreeCols, TwoCols,
    templateImageText, templateImageTextFull,
    templateRowImageRowText, templateTextImage, videoBlock, mapBlock
} from "../Blocks/Blocks";
import { handleAssetRemove } from "../Utils/AssetManager/AssetManagerUtils";
import { AssetManagerService } from "./AssetManagerService";
import { Toast } from "../../Toast";
import "../Utils/CKEditorPlugin/index"
import { componentsTypesPlugin } from "../Utils/ComponentsTypesPlugin/componentsTypesPlugin";
import { Messages } from "../Config/Messages";

export const initEditor = (entrypointPath, isSuperAdmin = false) => {

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
                templateRowImageRowText, templateTextImage, videoBlock, mapBlock]
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
        plugins: ['gjs-plugin-ckeditor5', componentsTypesPlugin, customCodePlugin],
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
            },
            [customCodePlugin]: {
                blockCustomCode: {
                    label: 'Code',
                    category: 'Contenu'
                },
                propsCustomCode: {
                    components: 'Votre code personnalisé'
                },
                modalTitle: 'Insérez votre code',
                buttonLabel: 'Sauvegarder'
            }
        }
    });

    editor.Panels.removePanel('options')

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

    if (!isSuperAdmin) editor.BlockManager.remove('custom-code')


    editor.on('storage:error:store', error => Toast.error(Messages.storage.store.error))
    editor.on('storage:error:load', error => Toast.error(Messages.storage.load.error))

    editor.on('asset:open', () => handleAssetRemove())
    editor.on('asset:upload:end', () => handleAssetRemove())
    editor.on('asset:upload:error', error => Toast.error(Messages.asset.upload.error))

    editor.on('asset:remove', async (asset) => {
        try {
            const assetUrl = asset.attributes.url;
            const res = await AssetManagerService.remove(assetUrl)
            if (res.status === 200) Toast.success(Messages.asset.remove.success)

        } catch (e) {
            // si erreur API, l'asset ne sera pas supprimée du serveur
            // mais correctement supprimée dans l'éditeur
            Toast.error(Messages.asset.remove.error)
        }
    })

    return editor

}