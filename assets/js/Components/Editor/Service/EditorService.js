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
            messages: {fr}
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
    });

    editor.BlockManager.add("h1", h1)
    editor.BlockManager.add("h2", h2)
    editor.BlockManager.add("h3", h3)
    editor.BlockManager.add("h4", h4)
    editor.BlockManager.add("h5", h5)
    editor.BlockManager.add("h6", h6)
    editor.BlockManager.add("text", text)
    editor.BlockManager.add("image", image)
    editor.BlockManager.add("OneCol", OneCol)
    editor.BlockManager.add("TwoCols", TwoCols)
    editor.BlockManager.add("ThreeCols", ThreeCols)
    editor.BlockManager.add("TextImg", templateTextImage)
    editor.BlockManager.add("ImgText", templateImageText)
    editor.BlockManager.add("RowImgRowText", templateRowImageRowText)
    editor.BlockManager.add("ImgFullText", templateImageTextFull)
    editor.BlockManager.add("Container", containerBlock)
    editor.BlockManager.add("ContainerFluid", containerFluidBlock)

    const panelManager = editor.Panels;
    panelManager.removePanel('options')
    panelManager.getButton('views', 'open-blocks').set('active', true)
    //panelManager.removeButton('views', 'open-sm')

    const styleManager = editor.StyleManager;
    styleManager.removeSector('general');
    styleManager.removeSector('flex');
    styleManager.removeSector('dimension');
    styleManager.removeSector('decorations');
    styleManager.removeSector('extra');

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