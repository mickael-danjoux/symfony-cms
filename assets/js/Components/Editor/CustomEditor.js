import "grapesjs/dist/css/grapes.min.css";

import { WebpackService } from "./Service/WebpackService";
import { initEditor } from "./Service/EditorService";
import { Toast } from "../Toast";
import initAdmin from "../../Utils/InitAdmin";

export class CustomEditor {
  static defineElement(elementName = "custom-editor-element") {

    class CustomEditorElement extends HTMLElement {

      mounted = false;
      editor = {};
      showEditor = true;
      isSuperAdmin = this.getAttribute("is-super-admin");

      /**
       * WebComponent method
       * @returns {string[]}
       */
      static get observedAttributes() {
        return ["display", "token"];
      }

      /**
       * WebComponent method
       * @returns {Promise<void>}
       */
      async connectedCallback() {
        this.render();
        this.mounted = true;
      }

      /**
       * WebComponent method
       * @param name
       * @param oldValue
       * @param newValue
       * @returns {boolean}
       */
      attributeChangedCallback(
        name,
        oldValue,
        newValue,
      ) {
        if (!this.mounted) {
          return false;
        }
        this.render();
      }

      /**
       * WebComponent method
       */
      disconnectedCallback() {
        this.mounted = false;
      }

      /**
       * WebComponent method
       */
      render() {
        if (!this.mounted) {
          this.createApp();
          this.buildEditor();
          this.buildApp();
        }
      }

      /**
       * Create the Editor instance
       */
      buildEditor = async () => {
        window.addEventListener("storeEditorContent", async () => await this.editor.store());
        let entrypointPath = null;
        try {
          const res = await WebpackService.getEntrypoint();
          entrypointPath = res.data.entrypoint;
          this.editor = initEditor(entrypointPath, this.isSuperAdmin);
        } catch (e) {
          console.error(e);
          Toast.error("Une erreur est survenue lors du chargement de l'éditeur. Veuillez recharger la page.");
        }
      };

      /**
       * Connect Editor instance with current page and actions
       */
      buildApp = () => {
        let formHasChanged = false;

        /**
         * Listen PHP form change
         */
        const handleFormChange = () => {
          document.querySelectorAll(".js-change").forEach((el) => {
            el.addEventListener("change", () => {
              disabledPreview();
            });
          });
        };

        /**
         * Disable page preview when important fields in PHP form change
         */
        const disabledPreview = () => {
          formHasChanged = true;
          displaySaveWarning();
          document.querySelectorAll(".js-disabled-on-change").forEach((el) => {
            el.classList.add("disabled");
          });
        };

        /**
         * Open preview
         */
        const preview = () => {
          if (!formHasChanged) {
            storeEditorContent();
            const path = document.getElementById("page_path").value;
            window.open("/" + path + "?preview=true", "_blank").focus();
          }
        };

        /**
         * Show alert message to force user save page
         */
        const displaySaveWarning = () => {
          document.querySelector(".js-save-warning").classList.remove("d-none");
        };

        /**
         * Events when user change page type (custom or internal)
         */
        const onSelectPageTypeChange = () => {
          const selectElement = document.getElementById("page_type");
          const INTERNAL_PAGE = "INTERNAL_PAGE";
          const CUSTOM_PAGE = "CUSTOM_PAGE";
          // si l'élément est défini, alors l'utilisateur est un SA
          if (selectElement) {
            const blockInputControllerElement = document.getElementById("form-page-controller");
            const blockInputRouteElement = document.getElementById("form-page-route");
            const inputControllerElement = document.getElementById("page_controller");
            const pageTypeValue = document.getElementById("page_type").value;
            // sert uniquement au chargement de la page
            if (pageTypeValue === INTERNAL_PAGE) {
              blockInputControllerElement.classList.remove("d-none");
              blockInputRouteElement.classList.remove("d-none");
              inputControllerElement.setAttribute("required", "required");
              this.showEditor = false;
              hideEditor();
            }
            selectElement.addEventListener("change", (e) => {
              const selectedValue = e.target.value;
              if (selectedValue === INTERNAL_PAGE) {
                blockInputControllerElement.classList.remove("d-none");
                blockInputRouteElement.classList.remove("d-none");
                inputControllerElement.value = "";
                inputControllerElement.setAttribute("required", "required");
                this.showEditor = false;
                hideEditor();
              } else if (selectedValue === CUSTOM_PAGE) {
                blockInputControllerElement.classList.add("d-none");
                blockInputRouteElement.classList.add("d-none");
                inputControllerElement.removeAttribute("required");
                inputControllerElement.value = "";
                this.showEditor = true;
                displayEditor();
              }
            });
          }
          // élément non défini (=user role ADMIN)
          else {
            // l'élément #pageType est un input:hidden pour récupérer le type de la page
            // sert uniquement au chargement de la page
            const pageTypeValue = document.getElementById("pageType").value;
            if (pageTypeValue === INTERNAL_PAGE) {
              this.showEditor = false;
              hideEditor();
            }
          }
        };

        /**
         * Actions to manage the page URL (auto or manual) when PHP form has changed
         */
        const manageUrlField = () => {
          const titleField = document.getElementById("page_title");
          const pathField = document.getElementById("page_path");
          const pathPreview = document.getElementById("path-preview");
          const customPathField = document.getElementById("page_customPath");
          const editPathButton = document.getElementById("editPath");
          const reloadPathButton = document.getElementById("reloadPath");
          if (customPathField.checked) {
            pathField.classList.remove("d-none");
            pathPreview.classList.add("d-none");
            editPathButton.classList.add("d-none");
            reloadPathButton.classList.remove("d-none");
          }
          titleField.addEventListener("change", (event) => {
            if (!customPathField.checked) {
              generateSlug();
            }
          });
          editPathButton.addEventListener("click", () => {
            pathField.classList.remove("d-none");
            pathPreview.classList.add("d-none");
            customPathField.checked = true;
          });
          reloadPathButton.addEventListener("click", () => {
            reloadPathButton.classList.add("d-none");
            pathField.classList.add("d-none");
            editPathButton.classList.remove("d-none");
            pathPreview.classList.remove("d-none");
            customPathField.checked = false;
            generateSlug();
          });

          function generateSlug() {
            const slug = slugger(titleField.value);
            pathField.value = slug;
            pathPreview.innerText = slug;
          }
        };

        const displayEditor = () => {
          document.querySelector(elementName).classList.remove("d-none");
        };

        const hideEditor = () => {
          document.querySelector(elementName).classList.add("d-none");
        };


        const storeEditorContent = () => window.dispatchEvent(new CustomEvent("storeEditorContent"));

        document.querySelector(".js-preview").addEventListener("click", () => {
          preview();
        });


        initAdmin();
        handleFormChange();
        onSelectPageTypeChange();
        manageUrlField();
      };


      createApp() {

        this.innerHTML = `
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
          </style>
          <div id="gjs"></div>
        `;
      }
    }

    if (!customElements.get("custom-editor-element")) {
      customElements.define(elementName, CustomEditorElement);
    }
  }
}




