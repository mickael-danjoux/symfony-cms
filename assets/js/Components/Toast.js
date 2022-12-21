import Toastify from 'toastify-js'
import "toastify-js/src/toastify.css"

export const toastConfig = {
    text: "",
    duration: 4000,
    newWindow: true,
    close: true,
    gravity: "top", // `top` or `bottom`
    position: "right", // `left`, `center` or `right`
    stopOnFocus: true, // Prevents dismissing of toast on hover
    offset: {
        x: 10, // horizontal axis - can be a number or a string indicating unity. eg: '2em'
        y: 57 // vertical axis - can be a number or a string indicating unity. eg: '2em'
    },
    style: {
        background: "#00b09b",
    },
}



function success(message) {
    const successToastConfig =  {...toastConfig, text:message}
    Toastify(successToastConfig).showToast()
}

function error(message) {
    const successToastConfig =  {
        ...toastConfig,
        text:message,
        style: {
            background: "#c41616",
        },
    }
    Toastify(successToastConfig).showToast()
}

function info(message) {
    const successToastConfig =  {
        ...toastConfig,
        text:message,
        style: {
            background: "#1661c4",
        },
    }
    Toastify(successToastConfig).showToast()
}

function warning(message) {
    const successToastConfig =  {
        ...toastConfig,
        text:message,
        style: {
            background: "#fca311",
        },
    }
    Toastify(successToastConfig).showToast()
}

function custom(toastConfig){
    Toastify(toastConfig).showToast()
}

export const Toast = {
    success,
    error,
    info,
    warning,
    custom
}