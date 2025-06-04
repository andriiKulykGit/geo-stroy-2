const init = () => {
  const items = document.querySelectorAll(".select");

  const closeAllDropdowns = () => {
    items.forEach((item) => {
      item.classList.remove("is-expanded");
      item.querySelector(".select__dropdown").style.display = "none";
    });
  };

  items.forEach((item) => {
    const box = item.querySelector(".select__box");
    const dropdown = item.querySelector(".select__dropdown");

    box.addEventListener("click", (e) => {
      e.stopPropagation();

      if (!item.classList.contains("is-expanded")) {
        closeAllDropdowns();
        item.classList.add("is-expanded");
        dropdown.style.display = "block";
      } else {
        item.classList.remove("is-expanded");
        dropdown.style.display = "none";
      }
    });
  });

  document.addEventListener("click", (e) => {
    let item = e.target.closest(".select__item");

    if (item) {
      closeAllDropdowns();

      const selectValue = item
        .closest(".select")
        .querySelector(".select__value");

      selectValue.innerHTML = item.children[0].outerHTML;
      selectValue.setAttribute("data-value", item.dataset.value);
    }
  });
};

export { init };
