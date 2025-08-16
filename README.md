````markdown
# Tasker - Simple Laravel Task Management App

Tasker is a lightweight Laravel web application for managing tasks. Features include:

- Create, edit, and delete projects
- Create, edit, and delete tasks
- Reorder tasks via drag-and-drop
- Tasks stored in MySQL with automatic priority management
- Fully functional in Laravel Sail Docker environment
- Per project task management
---

## Prerequisites

- Docker & Docker Compose installed
- PHP 8.1+ (handled via Sail)
- Composer

---

## Installation (from Git)

1. **Clone the repository**

```bash
git clone <your-repo-url> tasker
cd tasker
````

2. **Install dependencies via Composer**

```bash
composer install
```

---

## Installation (from ZIP)

1. **Download the ZIP** of the project and extract it:

```bash
unzip tasker.zip -d tasker
cd tasker
```

2. **Install Composer dependencies**

```bash
composer install
```

---

## Environment Setup

1. **Create `.env` file**

```bash
cp .env.example .env
```

2. **Update database settings** in `.env` (Sail defaults):

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=tasker
DB_USERNAME=sail
DB_PASSWORD=password
```

---

## Running with Laravel Sail

1. **Start Sail containers**

```bash
./vendor/bin/sail up -d
```

2. **Generate application key**

```bash
./vendor/bin/sail artisan key:generate
```

3. **Run database migrations**

```bash
./vendor/bin/sail artisan migrate
```

---

## Usage

* Open your browser at `http://localhost` (or the port Sail provides)
* **Add a project:** Click `Create Project` and submit a task name
* **Select a project:** Select a project from the "Current Project" dropdown menu
* **Edit a Project:** Click `Edit` on button bar below project dropdown
* **Delete a Project:** Click `Delete` on button bar below project dropdown
* **Add a task:** Click `Add Task` and submit a task name
* **Edit a task:** Click `Edit` next to a task
* **Delete a task:** Click `Delete` next to a task
* **Reorder tasks:** Drag and drop tasks in the list — the order is automatically saved

---

## Notes

* Drag-and-drop is implemented using **native HTML5 Drag & Drop API** — no external JS libraries required.
* Task priority is automatically updated in the database when reordered.
* Laravel Sail handles all Docker configuration.

---

## Stopping the Application

```bash
./vendor/bin/sail down
```

---

## License

MIT License

```

---