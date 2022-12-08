<script setup>
import "grapesjs/dist/css/grapes.min.css"
import { onMounted, reactive, defineProps } from "vue";
import { Toast } from "../Toast";
import { WebpackService } from "./Service/WebpackService";
import { initEditor } from "./Service/EditorService";


// Sauvegarde des data de l'éditeur lors de la soumission du formulaire de la page
// ps: les données de l'éditeur sont save après chaque modification (nb de steps avant save modifiable)
window.addEventListener('storeEditorContent', async () => await editor.store())

let editor = reactive({})

const props = defineProps({
	isSuperAdmin: {
		type: Boolean
	}
})

onMounted( async () => {
	let entrypointPath = null
	try {
		const res = await WebpackService.getEntrypoint()
		entrypointPath = res.data.entrypoint
		editor = initEditor(entrypointPath, props.isSuperAdmin)
	} catch (e) {
		console.error(e)
		Toast.error('Une erreur est survenue lors du chargement de l\'éditeur. Veuillez recharger la page.')
	}
});


/*const getHtml = () => {
	const projectData = editor.getProjectData();
	console.log(projectData)

	const pagesHtml = editor.Pages.getAll().map(page => {
		const component = page.getMainComponent()
		return {
			html: editor.getHtml({component}),
			css: editor.getCss({component}),
		}
	})
	return { id: 'demo', projectData, pagesHtml };
}*/
</script>


<template>
	<div id="gjs"></div>
</template>

<style>
.gjs-blocks-c {
	justify-content: center;
	height: auto;
	padding: 10px;
}

.gjs-block {
	width: auto;
	font-size: 15px;
	justify-content: center;
	height: auto;
	min-height: auto;
}

.gjs-am-assets-header {
	display: none;
}

.gjs-rte-toolbar {
	display: none;
}

/**[data-gjs-highlightable] {*/
/*	outline: 1px dashed rgba(170,170,170,0.7);*/
/*	outline-offset: -2px;*/
/*}*/

/* Class pour modifié l'apparence du panel */
/*.gjs-one-bg {
	background-color: #242A3B;
}

.gjs-two-color {
	color: #9ca8bb;
}

.gjs-three-bg {
	background-color: #1E8FE1;
	color: white;
}

.gjs-four-color,
.gjs-four-color-h:hover {
	color: #1E8FE1;
}*/
</style>