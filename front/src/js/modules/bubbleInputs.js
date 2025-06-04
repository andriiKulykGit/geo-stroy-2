const init = () => {
  const bubbleInputs = document.querySelectorAll('.input__control_bubble')

  if (!bubbleInputs.length) return

  const container = bubbleInputs[0].closest('.bubble-container') || bubbleInputs[0].parentElement
  let hiddenInput = container.querySelector('input[type="hidden"]')
  if (!hiddenInput) {
    hiddenInput = document.createElement('input')
    hiddenInput.type = 'hidden'
    hiddenInput.name = container.dataset.name || 'code'
    container.appendChild(hiddenInput)
  }

  const updateHiddenValue = () => {
    const digits = Array.from(bubbleInputs)
      .map((input) => input.value)
      .join('')

    hiddenInput.value = digits
  }

  bubbleInputs.forEach((input, index) => {
    input.addEventListener('input', (e) => {
      e.target.value = e.target.value.replace(/[^0-9]/g, '')

      if (e.target.value && index < bubbleInputs.length - 1) {
        bubbleInputs[index + 1].focus()
      }

      updateHiddenValue()
    })
    input.addEventListener('keyup', (e) => {
      if ((e.key === 'Backspace' || e.key === 'Delete') && !e.target.value && index > 0) {
        const prevInput = bubbleInputs[index - 1]
        prevInput.focus()

        const len = prevInput.value.length
        prevInput.setSelectionRange(len, len)
      }
    })
    input.addEventListener('paste', (e) => {
      e.preventDefault()

      let pastedData = ''

      if (e.clipboardData || window.clipboardData) {
        pastedData = (e.clipboardData || window.clipboardData).getData('text')
      }

      if (pastedData) {
        const digits = pastedData.replace(/[^0-9]/g, '').slice(0, 4)

        if (digits) {
          digits.split('').forEach((digit, i) => {
            if (i < bubbleInputs.length) {
              bubbleInputs[i].value = digit
            }
          })

          const focusIndex = Math.min(index + digits.length, bubbleInputs.length - 1)

          setTimeout(() => bubbleInputs[focusIndex].focus(), 0)

          updateHiddenValue()
        }
      }
    })

    input.addEventListener('touchend', () => {
      input.focus()
    })
  })

  updateHiddenValue()
}

export { init }
