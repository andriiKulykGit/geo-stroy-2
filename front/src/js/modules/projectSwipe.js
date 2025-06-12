import Hammer from "hammerjs";
const init = () => {
  const items = document.querySelectorAll(".project__inner");

  if (items) {
    items.forEach((item) => {
      const parent = item.parentElement;
      const hammer = new Hammer(item);
      const maxSwipe = 120;
      const threshold = 0.8;
      let initialX = null;
      let isSwipe = false;

      hammer.on("panstart", (e) => {
        initialX = item.offsetLeft;
        isSwipe = true;
      });

      const allowLeftSwipe = !parent.classList.contains("project_trash");
      const allowRightSwipe = !parent.classList.contains("project_favorite");

      if (allowLeftSwipe) {
        hammer.on("panleft", (e) => {
          e.preventDefault();
          const deltaX = e.deltaX;
          const newX = Math.max(Math.min(deltaX, maxSwipe), -maxSwipe);
          parent.classList.add("project_swipe");
          item.style.transform = `translateX(${newX}px)`;
        });
      }

      if (allowRightSwipe) {
        hammer.on("panright", (e) => {
          e.preventDefault();
          const deltaX = e.deltaX;
          const newX = Math.max(Math.min(deltaX, maxSwipe), -maxSwipe);
          parent.classList.add("project_swipe");
          item.style.transform = `translateX(${newX}px)`;
        });
      }

      hammer.on("panend", (e) => {
        e.preventDefault();
        const deltaX = Math.abs(e.deltaX);

        parent.classList.remove("project_swipe");

        item.style.transition = "transform 0.3s ease";
        item.style.transform = "translateX(0)";

        const isToTrashProject = parent.classList.contains("project_trash");
        const isToFavoriteProject =
          parent.classList.contains("project_favorite");

        if (
          (deltaX > maxSwipe * threshold && e.deltaX > 0 && isToTrashProject) ||
          (deltaX > maxSwipe * threshold && e.deltaX < 0 && isToFavoriteProject)
        ) {
          // Получаем ID проекта из атрибута data-id или из URL
          const projectId =
            parent.getAttribute("data-id") ||
            parent.href.split("/").pop().replace(/\D/g, "");

          if (!projectId) {
            console.error("Не удалось получить ID проекта");
            return;
          }

          // Создаем объект FormData для отправки данных
          const formData = new FormData();
          formData.append("project_id", projectId);

          // Отправляем AJAX-запрос
          fetch("/front/toggle_favorite.php", {
            method: "POST",
            body: formData,
            credentials: "same-origin",
          })
            .then((response) => response.json())
            .then((data) => {
              if (data.success) {
                parent.classList.toggle("project_is-favorite");

                if (parent.classList.contains("project_trash")) {
                  parent.style.transitionProperty = "opacity";
                  parent.style.opacity = "0";

                  setTimeout(() => {
                    parent.remove();
                  }, 300);
                }
              } else {
                alert("Ошибка: " + data.message);
              }
            })
            .catch((error) => {
              console.error("Ошибка при выполнении запроса:", error);
              alert("Произошла ошибка при обработке запроса");
            });
        }

        setTimeout(() => {
          item.style.transition = "none";
          setTimeout(() => {
            isSwipe = false;
          }, 50);
        }, 300);
      });

      parent.addEventListener("click", (e) => {
        if (isSwipe) {
          e.preventDefault();
          e.stopPropagation();
        }
      });
    });
  }
};

export { init };
