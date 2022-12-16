export default () => {
    const dropAreaEventsOn = ['dragenter', 'focus', 'click']
    const dropAreaEventsOff = ['dragleave', 'blur', 'drop']

    document.querySelectorAll('.file-drop-area').forEach(function(dropArea) {
        const fileInput = dropArea.querySelector('.file-input')
        const fileMsg   = dropArea.querySelector('.file-msg')
        const deleteBtn = dropArea.querySelector('.item-delete')

        const fileMsgInit       = dropArea.dataset.textInit
        const fileMsgMultiple   = dropArea.dataset.textMultiple

        fileMsg.textContent = fileMsgInit

        dropAreaEventsOn.forEach(function (eventType) {
            fileInput.addEventListener(eventType, function () {
                dropArea.classList.add('is-active')
            })
        })

        dropAreaEventsOff.forEach(function (eventType) {
            fileInput.addEventListener(eventType, function () {
                dropArea.classList.remove('is-active')
            })
        })

        fileInput.addEventListener('change', function () {
            let filesCount = this.files.length

            if (filesCount === 1) {
                fileMsg.textContent = this.value.split('\\').pop()
                deleteBtn.style.display = 'inline-block'
            } else if (filesCount === 0) {
                fileMsg.textContent = fileMsgInit
                deleteBtn.style.display = 'none'
            } else {
                fileMsg.textContent = filesCount + ' ' + fileMsgMultiple
                deleteBtn.style.display = 'inline-block'
            }
        })

        deleteBtn.addEventListener('click', function() {
            fileInput.value = null
            fileMsg.textContent = fileMsgInit
            deleteBtn.style.display = 'none'
        })
    })

}
