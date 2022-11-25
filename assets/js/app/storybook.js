import './main'
import {createApp, onMounted} from "vue";

createApp({
    compilerOptions: {
        delimiters: ["${", "}"]
    },
    setup(){
        const copyContent = (id) => {
           const el = document.getElementById(id)
            navigator.clipboard.writeText(el.innerText)
           const button = document.querySelector(`#${id} button.copy`)
            button.classList.add('copied')
            setTimeout(function() {
                button.classList.remove('copied')
            }, 1000);
        }
        return {
            copyContent
        }
    }
}).mount('main')
