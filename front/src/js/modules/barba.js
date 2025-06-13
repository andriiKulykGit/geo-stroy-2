import barba from '@barba/core'
import gsap from 'gsap'
import { init as initBubbleInputs } from './bubbleInputs.js'
import { init as initProjectSwipe } from './projectSwipe.js'
import { init as initDropdown } from './dropdown.js'
import { setDelay } from './aos.js'

const transitionOptions = {
  sync: true,
  leave(data) {
    return gsap.to(data.current.container, {
      opacity: 0,
    })
  },
  enter(data) {
    initBubbleInputs()
    initProjectSwipe()
    initDropdown()
    setDelay()

    return gsap.from(data.next.container, {
      opacity: 0,
      onComplete: () => {
        updateAfterContentMargin()
      },
    })
  }
}

const init = () => {
  barba.init({
    transitions: [
      {
        name: 'default-transition',
        ...transitionOptions,
      },
      {
        name: 'self',
        ...transitionOptions,
      },
    ],
  })
}

function updateAfterContentMargin() {
  const barbaContainer = document.querySelector('[data-barba="container"]')
  const afterContent = document.querySelector('.after-barba-content')

  if (barbaContainer && afterContent) {
    const containerHeight = barbaContainer.offsetHeight
    afterContent.style.marginTop = `${containerHeight}px`
  }
}

export { init }
