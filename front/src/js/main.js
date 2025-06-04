'use strict'

import { init as initBubbleInputs } from './modules/bubbleInputs.js'
import { init as initProjectSwipe } from './modules/projectSwipe.js'
import { init as initDropdown } from './modules/dropdown.js'
import { init as initAos } from './modules/aos.js'
import { init as initBarba } from './modules/barba.js'

document.addEventListener('DOMContentLoaded', () => {
  initBubbleInputs()
  initProjectSwipe()
  initDropdown()
  initAos()
  initBarba()
})
