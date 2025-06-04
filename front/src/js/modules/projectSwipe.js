import Hammer from 'hammerjs'
const init = () => {
  const items = document.querySelectorAll('.project__inner')

  if (items) {
    items.forEach((item) => {
      const parent = item.parentElement
      const hammer = new Hammer(item)
      const maxSwipe = 120
      const threshold = 0.8
      let initialX = 0
      let isSwipe = false 

      hammer.on('panstart', (e) => {
        initialX = item.offsetLeft
        isSwipe = true
      })

      const allowLeftSwipe = !parent.classList.contains('project_trash')
      const allowRightSwipe = !parent.classList.contains('project_favorite')

      if (allowLeftSwipe) {
        hammer.on('panleft', (e) => {
          e.preventDefault()
          const deltaX = e.deltaX
          const newX = Math.max(Math.min(deltaX, maxSwipe), -maxSwipe)
          parent.classList.add('project_swipe')
          item.style.transform = `translateX(${newX}px)`
        })
      }

      if (allowRightSwipe) {
        hammer.on('panright', (e) => {
          e.preventDefault()
          const deltaX = e.deltaX
          const newX = Math.max(Math.min(deltaX, maxSwipe), -maxSwipe)
          parent.classList.add('project_swipe')
          item.style.transform = `translateX(${newX}px)`
        })
      }

      hammer.on('panend', (e) => {
        e.preventDefault()
        const deltaX = Math.abs(e.deltaX)
        const direction = e.deltaX > 0 ? 'В избранное' : 'Удалить'

        parent.classList.remove('project_swipe')

        item.style.transition = 'transform 0.3s ease'
        item.style.transform = 'translateX(0)'

        const isToTrashProject = parent.classList.contains('project_trash')
        const isToFavoriteProject = parent.classList.contains('project_favorite')

        if (deltaX > maxSwipe * threshold && e.deltaX > 0 &&
          isToTrashProject
        ) {
            alert('Удалить')
        }
        
        if (deltaX > maxSwipe * threshold && e.deltaX < 0 &&
          isToFavoriteProject
        ) {
            alert('В избранное')
        }

        setTimeout(() => {
          item.style.transition = 'none'
          setTimeout(() => {
            isSwipe = false
          }, 50)
        }, 300)
      })

      // Предотвращаем переход по ссылке при свайпе
      parent.addEventListener('click', (e) => {
        if (isSwipe) {
          e.preventDefault()
          e.stopPropagation()
        }
      })
    })
  }
}

export { init }
