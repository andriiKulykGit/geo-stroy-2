const init = () => {
  const inputEl = document.querySelector(".dropzone__input");

  if (!inputEl) return;

  inputEl.addEventListener("change", () => {
    const files = Array.from(inputEl.files);
    const progressList = document.querySelector(".create__files");

    files.forEach((file, index) => {
      const fileId = "file-" + index;

      const formatBytes = (bytes) => {
        if (bytes === 0) return "0 B";
        const sizes = ["B", "KB", "MB", "GB"];
        const i = Math.floor(Math.log(bytes) / Math.log(1024));
        return (bytes / Math.pow(1024, i)).toFixed(2) + " " + sizes[i];
      };

      const size = formatBytes(file.size);

      const fileEL = createFileEl(file.name, size, progressList, fileId);

      const formData = new FormData();
      formData.append("file", file);

      const xhr = new XMLHttpRequest();
      const progressBar = fileEL.querySelector(".progress-bar__fill");

      xhr.upload.onprogress = function (event) {
        if (event.lengthComputable) {
          const percent = Math.round((event.loaded / event.total) * 100);
          progressBar.style.width = percent + "%";
        }
      };

      xhr.onload = function () {
        if (xhr.status === 200) {
          setFileSuccess(fileEL);
          progressBar.style.width = "100%";

          try {
            const response = JSON.parse(xhr.responseText);
            if (response.success && response.filename) {
              const event = new CustomEvent("fileUploaded", {
                detail: {
                  filename: response.filename,
                },
              });
              document.dispatchEvent(event);
            }
          } catch (e) {
            console.error("Ошибка при обработке ответа сервера:", e);
          }
        } else {
          setFileError(fileEL);
          alert(`Ошибка загрузки файла ${file.name}: ${xhr.statusText}`);
        }
      };

      xhr.onerror = function () {
        setFileError(fileEL);
        alert(`Ошибка соединения при загрузке файла ${file.name}`);
      };

      xhr.open("POST", "upload.php");
      xhr.send(formData);

      function createFileEl(name, size, parent, fileId) {
        const fileEl = document.createElement("div");
        fileEl.id = fileId;
        fileEl.classList.add("file", "file_loading");
        fileEl.setAttribute("data-aos", "fade-up");

        fileEl.innerHTML = `<div class="file__icon">
            <span class="icon icon_file icon_huge"></span>
          </div>
          <div class="file__inner">
            <div class="file__name">
              <span>${name}</span>
              <button type="button" class="file__btn">
                <span class="icon icon_medium icon_trash-outline icon_current-color"></span>
              </button>
            </div>
            <ul class="file__subtext">
              <li class="file__item">${size}</li>
              <li class="file__item file__state">
                <span class="icon icon_small icon_loader"></span>
                <span class="file__state-text">Загрузка...</span>
              </li>
            </ul>
          </div>
          <div class="file__progress-bar">
            <div class="progress-bar">
              <div class="progress-bar__fill"></div>
            </div>
          </div>`;

        parent.appendChild(fileEl);

        const deleteBtn = fileEl.querySelector(".file__btn");
        deleteBtn.addEventListener("click", function () {
          const filename = name;

          fetch("/front/delete_file.php", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify({ filename: filename }),
          })
            .then((response) => response.json())
            .then((data) => {
              if (data.success) {
                fileEl.remove();

                const event = new CustomEvent("fileRemoved", {
                  detail: {
                    filename: filename,
                  },
                });
                document.dispatchEvent(event);
              } else {
                alert(`Помилка видалення файлу: ${data.message}`);
              }
            })
            .catch((error) => {
              console.error("Помилка при видаленні файлу:", error);
              alert("Помилка при видаленні файлу");
            });
        });

        return fileEl;
      }

      function setFileSuccess(fileEl) {
        const icon = fileEl.querySelector(".file__state .icon");
        fileEL.classList.remove("file_loading");
        fileEl.classList.add("file_success");
        fileEl.querySelector(".file__state-text").textContent = "Завершено";
        icon.classList.remove("icon_loader");
        icon.classList.add("icon_circle-success");
      }

      function setFileError(fileEl) {
        const icon = fileEl.querySelector(".file__state.icon");
        fileEL.classList.remove("file_loading");
        fileEl.classList.add("file_error");
        fileEl.querySelector(".file__state-text").textContent = "Ошибка";
        icon.classList.remove("icon_loader");
        icon.classList.add("icon_circle-error");
      }
    });
  });
};

export { init };
