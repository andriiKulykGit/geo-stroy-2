const init = () => {
  const items = document.querySelectorAll('.select')

  const closeAllDropdowns = () => {
    items.forEach((item) => {
      item.classList.remove('is-expanded')
      item.querySelector('.select__dropdown').style.display = 'none'
    })
  }

  items.forEach((item) => {
    const box = item.querySelector('.select__box')
    const dropdown = item.querySelector('.select__dropdown')

    box.addEventListener('click', (e) => {
      e.stopPropagation() // Prevent event bubbling
      
      if (!item.classList.contains('is-expanded')) {
        closeAllDropdowns()
        item.classList.add('is-expanded')
        dropdown.style.display = 'block'
      } else {
        item.classList.remove('is-expanded')
        dropdown.style.display = 'none'
      }
    })
  })

  document.addEventListener('click', (e) => {
    const item = e.target.closest('.select__item')
    if (item) {
      closeAllDropdowns()

      item.closest('.select').querySelector('.select__value').innerHTML = item.children[0].outerHTML
    }
  })
}

export { init }
