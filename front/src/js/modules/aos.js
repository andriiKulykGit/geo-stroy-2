import AOS from 'aos'

const init = () => {
  const items = document.querySelectorAll('[data-aos]')

  AOS.init({
    once: true,
    offset: 1,
    throttleDelay: 0,
  })
}

const setDelay = () => {
  const items = [...document.querySelectorAll('[data-aos]')]
  items.forEach((item, index) => item.setAttribute('data-aos-delay', index * 50))
}

setDelay()

export { init, setDelay }
