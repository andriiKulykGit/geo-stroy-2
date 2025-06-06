<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../db.php';

require_login();
$stmt = $pdo->query("SELECT * FROM projects ORDER BY created_at DESC");
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Обработка отправки формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $project_id = $_POST['project_id'];
  $state = $_POST['state'];
  $comment = $_POST['comment'];
  $user = current_user();
  $user_id = $user['id'];

  // Проверяем, что state соответствует допустимым значениям
  $valid_states = [
    'Разработка тех. проекта',
    'Разработка проекта ОВОС',
    'Проведение общ. слушаний',
    'Мобилизация',
    'Полевые работы',
    'Завершено'
  ];

  if (!in_array($state, $valid_states)) {
    set_flash('error', 'Выбран недопустимый статус');
    header("Location: create-report.php");
    exit;
  }

  // Получаем список загруженных файлов
  $uploaded_files = [];
  if (isset($_POST['uploaded_files']) && !empty($_POST['uploaded_files'])) {
    $uploaded_files = json_decode($_POST['uploaded_files'], true);
  }

  // Сохраняем отчет в базу данных
  $stmt = $pdo->prepare("INSERT INTO reports (project_id, user_id, state, comment, files, created_at) VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP)");
  $stmt->execute([$project_id, $user_id, $state, $comment, json_encode($uploaded_files)]);

  header("Location: success.php");
  exit;
}
?>

<?php require_once __DIR__ . '/parts/head.php'; ?>


<body>
  <?php
  if (isset($_GET['project_id'])) {
    $quary_project_id = $_GET['project_id'];
    $foundProject = null;

    foreach ($projects as $p) {
      if ($p['id'] == $quary_project_id) {
        $foundProject = $p;
        break;
      }
    }
  }
  ?>
  <div class="wrapper" data-barba="wrapper">
    <main class="main main_gray" data-barba="container" data-barba-namespace="login">
      <?php the_header('Создать отчет') ?>
      <div class="create">
        <div class="container">

          <form action="create-report.php" method="POST" class="create__body" id="form">
            <input type="hidden" name="uploaded_files" id="uploaded_files" value="[]">
            <div class="select" data-aos="fade-up" style="z-index:3">
              <div class="select__box">
                <div class="select__prepend">
                  <span class="icon icon_medium icon_location icon_current-color"></span>
                </div>
                <div class="select__inner">
                  <div
                    class="select__value"
                    id="project"
                    data-value="<?php if ($foundProject) echo $foundProject['id'] ?>"><?php
                                                                                      if ($foundProject) {
                                                                                        echo '<span>' . $foundProject['name'] . '</span>';
                                                                                      }
                                                                                      ?></div>
                  <div class="select__label">Проект</div>
                </div>
                <div class="select__chevron">
                  <span class="icon icon_medium icon_chevrown-down icon_current-color"></span>
                </div>
              </div>
              <div class="select__dropdown">
                <ul class="select__list">
                  <?php
                  foreach ($projects as $p) {
                  ?>
                    <li class="select__li">
                      <button type="button" class="select__item" data-value="<?= $p['id'] ?>"><span><?= $p['name'] ?></span></button>
                    </li>
                  <?php
                  }
                  ?>
                </ul>
              </div>
            </div>
            <input type="hidden" name="project_id" id="project_id">
            <input type="hidden" name="state" id="state_value">
            <div class="select" data-aos="fade-up">
              <div class="select__box">
                <div class="select__prepend">
                  <span class="icon icon_medium icon_checklist icon_current-color"></span>
                </div>
                <div class="select__inner">
                  <div class="select__value" id="state" data-value></div>
                  <div class="select__placeholder">Выберите статус</div>
                </div>
                <div class="select__chevron">
                  <span class="icon icon_medium icon_chevrown-down icon_current-color"></span>
                </div>
              </div>
              <div class="select__dropdown">
                <ul class="select__list">
                  <?php
                  $items = [
                    [
                      'text' => 'Разработка тех. проекта',
                      'modifier' => 'gray'
                    ],
                    [
                      'text' => 'Разработка проекта ОВОС',
                      'modifier' => 'blue'
                    ],
                    [
                      'text' => 'Проведение общ. слушаний',
                      'modifier' => 'purple'
                    ],
                    [
                      'text' => 'Мобилизация',
                      'modifier' => 'red'
                    ],
                    [
                      'text' => 'Полевые работы',
                      'modifier' => 'yellow'
                    ],
                    [
                      'text' => 'Завершено',
                      'modifier' => 'green'
                    ]
                  ];

                  foreach ($items as $item) {
                    echo '
                    <li class="select__li">
                      <button type="button" class="select__item" data-value="' . $item['text'] . '">
                        <span>
                          <div class="state state_' . $item['modifier'] . '">' . $item['text'] . '</div>
                        </span>
                      </button>
                    </li>
                    ';
                  }
                  ?>
                </ul>
              </div>
            </div>
            <div class="input" data-aos="fade-up">
              <textarea id="message" name="comment" placeholder="Комментарий к отчету" class="input__control " wrap="soft"></textarea>
              <label for="message" class="input__prepend">
                <span class="icon icon_comment icon_current-color icon_medium"></span>
              </label>
            </div>
            <label class="dropzone" data-aos="fade-up">
              <input
                type="file"
                accept=".doc,.docx,.png,.jpg,.jpeg,.pdf,.xls,.xlsx,.excel,.word,.txt"
                class="visually-hidden dropzone__input"
                multiple>
              <div class="dropzone__icon">
                <span class="icon icon_current-color icon_cloud icon_large"></span>
              </div>
              <div class="dropzone__inner">
                <div class="dropzone__title">Выберите файл</div>
                <div class="dropzone__subtitle">Форматы WORD, PNG, JPEG, PDF, EXCEL до 50 МБ.</div>
              </div>
              <div class="button button_small">Выбрать файл</div>
            </label>
            <div class="create__files"></div>
            <button class="button" type="submit" data-aos="fade-up">Отправить отчет</button>
          </form>
        </div>
      </div>
      <?php the_footer([1, 0, 1]) ?>
    </main>
  </div>
  <?php require_once __DIR__ . '/parts/scripts.php' ?>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const VALID_STATES = [
        'Разработка тех. проекта',
        'Разработка проекта ОВОС',
        'Проведение общ. слушаний',
        'Мобилизация',
        'Полевые работы',
        'Завершено'
      ];

      const elements = {
        form: document.getElementById('form'),
        project: document.getElementById('project'),
        state: document.getElementById('state'),
        message: document.getElementById('message'),
        dropzone: document.querySelector('.dropzone__input'),
        uploadedFiles: document.getElementById('uploaded_files'),
        projectId: document.getElementById('project_id'),
        stateValue: document.getElementById('state_value')
      };

      const files = [];

      document.querySelectorAll('.select__item[data-value]').forEach(item =>
        item.addEventListener('click', () => elements.projectId.value = item.dataset.value)
      );


      document.querySelectorAll('.select__item:not([data-value])').forEach(item =>
        item.addEventListener('click', () => {
          const stateText = item.querySelector('span div').textContent.trim();
          if (VALID_STATES.includes(stateText)) {
            elements.stateValue.value = stateText;
          } else {
            console.error('Invalid state:', stateText);
          }
        })
      );

      document.addEventListener('fileUploaded', ({
        detail
      }) => {
        if (detail?.filename) {
          files.push(detail.filename);
          elements.uploadedFiles.value = JSON.stringify(files);
        }
      });

      elements.form.addEventListener('submit', e => {
        const validations = [{
            field: elements.project.dataset.value,
            message: 'Выберите проект'
          },
          {
            field: elements.state.dataset.value,
            message: 'Выберите статус'
          },
          {
            field: elements.message.value,
            message: 'Введите комментарий'
          }
        ];

        const invalid = validations.find(({
          field
        }) => !field);
        if (invalid) {
          e.preventDefault();
          setError(invalid.message);
          return;
        }

        elements.projectId.value = elements.project.dataset.value;
        const stateValue = elements.state.dataset.value.trim();
        console.log(stateValue);


        if (!VALID_STATES.includes(stateValue)) {
          e.preventDefault();
          setError('Выбран недопустимый статус');
          return;
        }

        elements.stateValue.value = stateValue;
      });

      function setError(text) {
        let errorEl = document.getElementById('form-error')
        if (errorEl) errorEl.remove()

        let projectId = document.getElementById('project')
        let state = document.getElementById('state')
        let message = document.getElementById('message')

        errorEl = document.createElement('div')
        errorEl.id = 'form-error'
        errorEl.style.color = 'red'
        errorEl.textContent = text
        errorEl.setAttribute('data-aos', 'fade-left')

        elements.form.prepend(errorEl)
      }
    })
  </script>
</body>

</html>